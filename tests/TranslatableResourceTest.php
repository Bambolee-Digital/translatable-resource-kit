<?php

namespace BamboleeDigital\TranslatableResourceKit\Tests;

use Orchestra\Testbench\TestCase;
use BamboleeDigital\TranslatableResourceKit\TranslatableResource;
use BamboleeDigital\TranslatableResourceKit\TranslatesAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TranslatableResourceTest extends TestCase
{
    /** @test */
    public function it_translates_attributes_in_resource()
    {
        $model = new class extends Model {
            use TranslatesAttributes;

            protected $translatable = ['name'];

            protected $attributes = [
                'name' => '{"en":"English Name","es":"Nombre en Español"}',
                'non_translatable' => 'Static Value'
            ];
        };

        app()->setLocale('en');
        $resource = new TranslatableResource($model);
        $result = $resource->toArray(new Request());

        $this->assertEquals('English Name', $result['name']);
        $this->assertEquals('Static Value', $result['non_translatable']);

        app()->setLocale('es');
        $result = $resource->toArray(new Request());

        $this->assertEquals('Nombre en Español', $result['name']);
        $this->assertEquals('Static Value', $result['non_translatable']);
    }
}