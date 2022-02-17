<?php
namespace FaarenTech\FaarenSDK;

use FaarenTech\FaarenSDK\Http\Middleware\HandleApiTokenMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class FaarenSDKServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        JsonResource::withoutWrapping();

        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . "/../config/faaren-sdk.php" => config_path('faaren-sdk.php')
            ], 'config');
        }

        $router = $this->app->make(Router::class);
        $router->middlewareGroup('faaren', [
            HandleApiTokenMiddleware::class
        ]);
    }
}
