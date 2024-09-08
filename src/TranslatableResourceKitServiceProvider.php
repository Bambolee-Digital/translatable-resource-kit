<?php

namespace BamboleeDigital\TranslatableResourceKit;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale;

class TranslatableResourceKitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/translatable-resource-kit.php' => config_path('translatable-resource-kit.php'),
        ], 'config');

        // Register the middleware only if it's not disabled in the config
        if (!config('translatable-resource-kit.disable_middleware', false)) {
            $router = $this->app->make(Router::class);
            $middlewareGroup = config('translatable-resource-kit.middleware_group', 'api');
            $router->pushMiddlewareToGroup($middlewareGroup, SetLocale::class);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/translatable-resource-kit.php', 'translatable-resource-kit'
        );
    }
}