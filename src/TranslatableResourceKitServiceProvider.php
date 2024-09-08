<?php

namespace BamboleeDigital\TranslatableResourceKit;

use Illuminate\Support\ServiceProvider;
use BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale;

class TranslatableResourceKitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/translatable-resource-kit.php' => config_path('translatable-resource-kit.php'),
        ], 'config');

        // Register the middleware
        $this->app['router']->aliasMiddleware('set-locale', SetLocale::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/translatable-resource-kit.php', 'translatable-resource-kit'
        );
    }
}