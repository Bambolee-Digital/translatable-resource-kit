<?php

namespace BamboleeDigital\TranslatableResourceKit\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetLocale Middleware
 *
 * This middleware sets the application locale based on the 'lang' query parameter
 * or falls back to the default locale if the requested locale is not supported.
 *
 * @package BamboleeDigital\TranslatableResourceKit\Middleware
 */
class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the requested language
        $locale = $request->input('lang', config('app.locale'));

        // Check if the requested language is supported, otherwise set the fallback
        if (!in_array($locale, config('translatable-resource-kit.supported_locales', [config('app.fallback_locale')]))) {
            $locale = config('app.fallback_locale');
        }

        // Set the application locale
        app()->setLocale($locale);

        // Continue the request execution
        return $next($request);
    }
}