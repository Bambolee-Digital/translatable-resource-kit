<?php

namespace BamboleeDigital\TranslatableResourceKit\Tests\Middleware;

use Orchestra\Testbench\TestCase;
use BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SetLocaleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return ['BamboleeDigital\TranslatableResourceKit\TranslatableResourceKitServiceProvider'];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale', 'en');
        $app['config']->set('app.fallback_locale', 'en');
        $app['config']->set('translatable-resource-kit.supported_locales', ['en', 'es', 'fr']);
    }

    /** @test */
    public function it_sets_locale_from_query_parameter()
    {
        $request = new Request(['lang' => 'es']);
        $middleware = new SetLocale();

        $middleware->handle($request, function ($req) {
            $this->assertEquals('es', app()->getLocale());
        });
    }

    /** @test */
    public function it_uses_fallback_locale_for_unsupported_language()
    {
        $request = new Request(['lang' => 'de']);
        $middleware = new SetLocale();

        $middleware->handle($request, function ($req) {
            $this->assertEquals('en', app()->getLocale());
        });
    }

    /** @test */
    public function it_uses_default_locale_when_no_lang_parameter()
    {
        $request = new Request();
        $middleware = new SetLocale();

        $middleware->handle($request, function ($req) {
            $this->assertEquals('en', app()->getLocale());
        });
    }
}