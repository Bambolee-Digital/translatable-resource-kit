<?php

namespace BamboleeDigital\TranslatableResourceKit\Tests;

use Orchestra\Testbench\TestCase;
use BamboleeDigital\TranslatableResourceKit\TranslatableResourceKitServiceProvider;
use BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale;

class TranslatableResourceKitServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [TranslatableResourceKitServiceProvider::class];
    }

    /** @test */
    public function it_registers_config_file()
    {
        $this->assertArrayHasKey('translatable-resource-kit', $this->app['config']);
    }

    /** @test */
    public function it_registers_middleware()
    {
        $this->assertArrayHasKey('set-locale', $this->app['router']->getMiddleware());
        $this->assertEquals(SetLocale::class, $this->app['router']->getMiddleware()['set-locale']);
    }
}