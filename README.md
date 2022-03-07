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

### Middlewares

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

####Resource Collection:
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
File: `app/Exceptions/Handler` change the extends to ` \FaarenTech\FaarenSDK\Exceptions\Handler`

```php 

class Handler extends \FaarenTech\FaarenSDK\Exceptions\Handler
{
}
```
