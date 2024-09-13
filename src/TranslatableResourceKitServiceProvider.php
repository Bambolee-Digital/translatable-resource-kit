<?php

namespace BamboleeDigital\TranslatableResourceKit;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use BamboleeDigital\TranslatableResourceKit\Http\Middleware\SetLocale;

class TranslatableResourceKitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('translatable-resource-kit.debug', false)) {
            Log::info('Booting TranslatableResourceKitServiceProvider');
        }
        
        $this->publishes([
            __DIR__.'/../config/translatable-resource-kit.php' => config_path('translatable-resource-kit.php'),
        ], 'config');

        // Register the middleware only if it's not disabled in the config
        if (!config('translatable-resource-kit.disable_middleware', false)) {
            // Registrar o middleware no grupo especificado na configuração
            $router = $this->app->make(Router::class);
            $middlewareGroup = config('translatable-resource-kit.middleware_group', 'api');
            $router->aliasMiddleware('set_locale', SetLocale::class);
            $router->pushMiddlewareToGroup($middlewareGroup, 'set_locale');

            // Log para confirmar que o middleware foi registrado
            if (config('translatable-resource-kit.debug', false)) {
                Log::info("TranslatableResourceKit middleware registered in '{$middlewareGroup}' group");
            }
        } else {
            if (config('translatable-resource-kit.debug', false)) {
                Log::info('TranslatableResourceKit middleware disabled');
            }
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/translatable-resource-kit.php', 'translatable-resource-kit'
        );
    }
}