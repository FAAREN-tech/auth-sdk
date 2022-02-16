# FAAREN AuthSDK

## Install

```shell
composer require faaren-tech/auth-sdk

// choose FaarenTech/AuthSdk/AuthSdkServiceProvider
php artisan vendor:publish
```

Customize the `service_url` parameter in config `auth-sdk.php` or set the value `FAAREN_AUTH_SERVICE_URL` in your `.env` file.

## Usage

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

- [HasValidTokenMiddleware](src/Http/Middleware/HandleApiTokenMiddleware.php) => Checks if the given token is valid and attaches the token details to the current request. The details are available with `$request->api_token`.

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
