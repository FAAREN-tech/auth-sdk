# FAAREN SDK

## Installation

```shell
composer require faaren-tech/faaren-sdk

// choose FaarenTech/AuthSdk/AuthSdkServiceProvider
php artisan vendor:publish
```

Customize the `service_url` parameter in config `auth-sdk.php` or set the value `FAAREN_AUTH_SERVICE_URL` in your `.env` file.

## Usage

> Important! If you use this library in a docker context `FAAREN_AUTH_SERVICE_URL` has to be the IP address of your machine. Otherwise it will try to find a host in its own docker network.
> As an alternative you can add an `external_hosts` option to your docker-compose-container. 

You can use the package everywhere by simply initializing it:

```php

use FaarenTech\FaarenSDK\FaarenSdk;

class SomeClass {
    
    public function someFunction()
    {
        $sdk = FaarenSdk::init("yourApiToken");
        $token = $sdk->apiToken()->details();
        $permissions = $sdk->apiToken()->permissions();
    }
    
}
```

The Token-Object contains the following details, for example:

```json
{
    "uuid": "tok_22j8HPsYoOKXEMIPHNpHQeBdhKFP",
    "subsidiary_uuid": "subsi_3mz4r520mPr770ZUYtCRdJtFHz",
    "user_uuid": "null OR user_uuid",
    "name": "null OR name",
    "type": "APP_TOKEN OR PERSONAL_TOKEN",
    "permissions": [
        "user:delete",
        "apiToken:create",
        "apiToken:read",
        "apiToken:list",
        "user:update"
    ],
    "plainTextToken": "55|mySecretToken"
}
```

The listed permissions, subsidiary_uuid and user_uuid can be used to make your app-specific authorization.

### Notifications

#### Mailings

You can simply trigger notifications via the Notification Service:

```php
use FaarenTech\FaarenSDK\FaarenSdk;

class SomeClass {
    
    public function someFunction()
    {
        $sdk = FaarenSdk::init("yourApiToken");
        $mailing = $sdk
            ->notification()
            ->mail()
            ->setMailing('example')
            ->setMailData([
                "to" => "fabian@faaren.com",
                "from" => "example@faaren.com",
                "bcc" => "it@faaren.com",
                "whitelabel_config" => [
                    "foo" => "bar"
                ]
            ])
            ->send();
    }
    
}
```

If an error occurs while calling the Notification Service, a `\FaarenTech\FaarenSDK\Exceptions\NotificationServiceException` is thrown. The exception message will contain a hint what was going wrong, e.g. an validation error:

> "message": "Status 422: Notification could not be sent because: The to field is required.",


### Middlewares

#### [AcceptsJsonMiddleware](src/Http/Middleware/AcceptsJsonMiddleware.php)

#### [HasValidTokenMiddleware](src/Http/Middleware/HandleAppTokenMiddleware.php)
You can add the middleware group `faaren` to all routes you like. This middleware group contains the following middlewares:

- [HasValidTokenMiddleware](src/Http/Middleware/HandleAppTokenMiddleware.php) => Checks if the given token is valid and attaches the token details to the current request. The details are available with `$request->api_token`.

Using this middleware makes it easy to work with tokens. You can access the token details everywhere via the request object:

```php
// Route
// Assign middleware
Route::middleware('faaren')
    ->get('/your-endpoint', [SomeController::class, 'someAction']);

// Access in SomeController.php
class SomeController {
    public function someAction(\Illuminate\Http\Request $request) 
    {
        // The provided api token is valid
        // The ApiToken can be accessed via
        $token = $request->api_token;
    }
}

// Access in SomeFormRequest.php
class SomeFormRequest extends \Illuminate\Foundation\Http\FormRequest {
    public function authorize()
    {
        $token = $this->api_token;
    }
}

// Or everywhere via request() helper
```
### Form Requests

This package provides a BaseRequest, the `FaarenRequest`. This Request implementes the `Illuminate\Contracts\Auth\Access\Authorizable` via the `Illuminate\Foundation\Auth\Access\Authorizable` trait. It can be used for any FormRequest you will create in your service.

This way it is possible to authorize incoming requests directly in your form request:
```php
use FaarenTech\FaarenSDK\Request\FaarenRequest;

class YourRequest extends FaarenRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->can('index', SomeModel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
```

In your policy you won't pass in an instance of your User. Instead you pass in an instance of your form request. The underlying policy:

```php
class YourPolicy
{
    use HandlesAuthorization;

    public function index(TemplateIndexRequest $request)
    {
        // Here you can access the methods from the FaarenRequest
        // Access the AppToken via $request->api_token;
        return true;
    }

    public function show(TemplateIndexRequest $request, SomeModel $someModel)
    {
        return true;
    }
}
```

### Define Resources 
Since version 1.1.0 we use "ResponseCollection" and "ResourceCollection" in our Resource-definitions. 

To use your own Resource with our Schema, you need to extend your Resource from "ResponseResource" and not from "JsonResource"
Then you need to change your "toArray" method to "toPayload"
Example: 
```php 
class YourAwesomeResource extends ResponseResource
{
    /**
     * @return array[]
     */
    public function toPayload()
    {
        return [
            'awesome' => true
        ];
    }
}
```

#### Resource Collection:
So your Resource gets mapped as YourAwesomeResourceCollection
```php 
class YourAwesomeResourceCollection extends \FaarenTech\FaarenSDK\Resources\ResponseCollection
{
    public $collects = YourAwesomeResource::class;
}
```

### Validation as Json in our Response-Schema
Your class needs to be extend from "FaarenRequest", and every validation error uses the schema
```php 
use FaarenTech\FaarenSDK\Request\FaarenRequest;

class ShowVehiclePoolRequest extends FaarenRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

}
```

### Handle Exceptions as Json

Simple add the [AcceptsJsonMiddleware](src/Http/Middleware/AcceptsJsonMiddleware.php) to the relevant middleware groups or as a global middleware.

> Using the custom [Handler](src/Exceptions/Handler.php) is deprecated and will cause errors when you use it!
