<?php

namespace BamboleeDigital\TranslatableResourceKit\Tests;

use Orchestra\Testbench\TestCase;
use BamboleeDigital\TranslatableResourceKit\TranslatesAttributes;
use Illuminate\Database\Eloquent\Model;

class TranslatesAttributesTest extends TestCase
{
    /** @test */
    public function it_can_translate_simple_attributes()
    {
        $model = new class extends Model {
            use TranslatesAttributes;

            protected $translatable = ['name'];

            protected $attributes = [
                'name' => '{"en":"English Name","es":"Nombre en Español"}'
            ];
        };

        app()->setLocale('en');
        $this->assertEquals('English Name', $model->translatedAttributes()['name']);

        app()->setLocale('es');
        $this->assertEquals('Nombre en Español', $model->translatedAttributes()['name']);
    }

    /** @test */
    public function it_can_handle_nested_translations()
    {
        $model = new class extends Model {
            use TranslatesAttributes;

            protected $translatable = ['details'];

            protected $attributes = [
                'details' => '{"en":{"title":"English Title","description":"English Description"},"es":{"title":"Título en Español","description":"Descripción en Español"}}'
            ];
        };

        app()->setLocale('en');
        $translated = $model->translatedAttributes();
        $this->assertEquals('English Title', $translated['details']['title']);
        $this->assertEquals('English Description', $translated['details']['description']);

        app()->setLocale('es');
        $translated = $model->translatedAttributes();
        $this->assertEquals('Título en Español', $translated['details']['title']);
        $this->assertEquals('Descripción en Español', $translated['details']['description']);
    }

    /** @test */
    public function it_respects_max_recursion_depth()
    {
        $model = new class extends Model {
            use TranslatesAttributes;

            protected $maxRecursionDepth = 2;

            public function relation1()
            {
                return $this->hasOne(self::class);
            }

            public function relation2()
            {
                return $this->hasOne(self::class);
            }

            protected $with = ['relation1', 'relation2'];
        };

        $result = $model->translatedAttributes();

        $this->assertArrayHasKey('relation1', $result);
        $this->assertArrayHasKey('relation2', $result['relation1']);
        $this->assertArrayNotHasKey('relation1', $result['relation1']['relation2']);
    }
}