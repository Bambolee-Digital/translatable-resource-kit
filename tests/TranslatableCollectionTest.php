<?php

namespace BamboleeDigital\TranslatableResourceKit\Tests;

use Orchestra\Testbench\TestCase;
use BamboleeDigital\TranslatableResourceKit\TranslatableCollection;
use BamboleeDigital\TranslatableResourceKit\TranslatesAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TranslatableCollectionTest extends TestCase
{
    /** @test */
    public function it_translates_collection_of_models()
    {
        $models = collect([
            new class extends Model {
                use TranslatesAttributes;

                protected $translatable = ['name'];

                protected $attributes = [
                    'name' => '{"en":"English Name 1","es":"Nombre en Español 1"}',
                ];
            },
            new class extends Model {
                use TranslatesAttributes;

                protected $translatable = ['name'];

                protected $attributes = [
                    'name' => '{"en":"English Name 2","es":"Nombre en Español 2"}',
                ];
            },
        ]);

        app()->setLocale('en');
        $collection = new TranslatableCollection($models);
        $result = $collection->toArray(new Request());

        $this->assertEquals('English Name 1', $result[0]['name']);
        $this->assertEquals('English Name 2', $result[1]['name']);

        app()->setLocale('es');
        $result = $collection->toArray(new Request());

        $this->assertEquals('Nombre en Español 1', $result[0]['name']);
        $this->assertEquals('Nombre en Español 2', $result[1]['name']);
    }
}