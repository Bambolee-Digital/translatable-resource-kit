# Bambolee Translatable Resource Kit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bambolee-digital/translatable-resource-kit.svg?style=flat-square)](https://packagist.org/packages/bambolee-digital/translatable-resource-kit)
[![Total Downloads](https://img.shields.io/packagist/dt/bambolee-digital/translatable-resource-kit.svg?style=flat-square)](https://packagist.org/packages/bambolee-digital/translatable-resource-kit)
[![License](https://img.shields.io/packagist/l/bambolee-digital/translatable-resource-kit.svg?style=flat-square)](https://packagist.org/packages/bambolee-digital/translatable-resource-kit)

The Bambolee Translatable Resource Kit is a powerful extension for Laravel applications using Spatie's Laravel Translatable package. It simplifies the handling of translated attributes in API responses and dynamic JSON structures, making it easier to develop multilingual applications.

## Features

- Seamless integration with Spatie's Laravel Translatable
- Dynamic handling of translated attributes in JSON responses
- Support for nested relations and deep translation
- Customizable recursion depth for nested translations
- Easy-to-use Resource and Collection classes for API responses
- Compatible with Laravel 8.x, 9.x, 10.x, and 11.x

## Installation

You can install the package via composer:

```bash
composer require bambolee-digital/translatable-resource-kit
```

## Configuration

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="BamboleeDigital\TranslatableResourceKit\TranslatableResourceKitServiceProvider" --tag="config"
```

This will create a `config/translatable-resource-kit.php` file where you can customize the behavior of the package.

### Disabling the Middleware

By default, the package registers the `set-locale` middleware automatically. If you want to disable this behavior, you can set the `disable_middleware` option to `true` in your `config/translatable-resource-kit.php` file:

```php
return [
    // ...
    'disable_middleware' => true,
    // ...
];
```

## Usage

### 1. Use the TranslatesAttributes trait in your model:

```php
use BamboleeDigital\TranslatableResourceKit\TranslatesAttributes;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations, TranslatesAttributes;

    public $translatable = ['name', 'description'];

    // ...
}
```

### 2. Create a Resource for your model:

```php
use BamboleeDigital\TranslatableResourceKit\TranslatableResource;

class ProductResource extends TranslatableResource
{
    // You can add custom logic here if needed
}
```

### 3. Create a Collection for your model:

```php
use BamboleeDigital\TranslatableResourceKit\TranslatableCollection;

class ProductCollection extends TranslatableCollection
{
    // You can add custom logic here if needed
}
```

### 4. Use in your controller:

```php
public function index()
{
    $products = Product::all();
    return new ProductCollection($products);
}

public function show(Product $product)
{
    return new ProductResource($product);
}
```

### 5. Using the middleware:

The `set-locale` middleware is automatically registered by the package. You can use it in your routes or route groups:

```php
Route::middleware(['set-locale'])->group(function () {
    // Your routes here
});
```

With this middleware in place, you can set the locale by adding a `lang` query parameter to your requests, e.g., `?lang=pt` for Portuguese. This allows easy language switching in your API calls.

If you've disabled the automatic middleware registration, you'll need to register it manually in your `app/Http/Kernel.php` file:

```php
protected $routeMiddleware = [
    // ...
    'set-locale' => \BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale::class,
];
```

## Example

Here's an example of how the JSON response changes when using this package:

Before:

```json
{
  "id": 1,
  "name": {
    "en": "Laptop",
    "es": "Portátil",
    "pt": "Notebook"
  },
  "description": {
    "en": "Powerful laptop for professionals",
    "es": "Portátil potente para profesionales",
    "pt": "Notebook potente para profissionais"
  },
  "price": 999.99
}
```

After (assuming the current locale is 'pt', set via `?lang=pt`):

```json
{
  "id": 1,
  "name": "Notebook",
  "description": "Notebook potente para profissionais",
  "price": 999.99
}
```

As you can see, the translated fields are automatically resolved to the current locale, simplifying the structure and making it easier to work with in frontend applications.

## Advanced Usage

### Customizing Recursion Depth

You can customize the maximum recursion depth for nested translations in the `config/translatable-resource-kit.php` file:

```php
return [
    'max_recursion_depth' => 3, // Default is 5
];
```

### Handling Nested Relations

The `TranslatesAttributes` trait automatically handles nested relations. Make sure your relations are properly defined in your model's `$with` property:

```php
class Product extends Model
{
    use HasTranslations, TranslatesAttributes;

    protected $with = ['category', 'tags'];

    // ...
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email security@bambolee.digital instead of using the issue tracker.

## Credits

- [Bambolee Digital](https://github.com/bambolee-digital)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.