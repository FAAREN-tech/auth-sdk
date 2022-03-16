<?php
namespace FaarenTech\FaarenSDK;

use FaarenTech\FaarenSDK\Http\Middleware\HandleAppTokenMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class FaarenSDKServiceProvider extends ServiceProvider
{
    /**
     * Registers all required assets
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/faaren-sdk.php', 'faaren-sdk');
    }

    /**
     * Called on application boot
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
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
            HandleAppTokenMiddleware::class
        ]);
    }
}
