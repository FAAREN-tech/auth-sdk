<?php
namespace FaarenTech\AuthSdk;

use Illuminate\Support\ServiceProvider;

class AuthSdkServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . "/../config/auth-sdk.php" => config_path('auth-sdk.php')
            ], 'config');
        }
    }
}
