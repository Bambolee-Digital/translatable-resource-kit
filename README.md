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

You can customize the middleware behavior in this configuration file:

```php
return [
    // Disable automatic middleware registration
    'disable_middleware' => false,

    // Specify the middleware group (default is 'api')
    'middleware_group' => 'api',

    // ... other configurations
];
```

### Disabling the Middleware

If you want to disable the automatic registration of the middleware, set the `disable_middleware` option to `true` in your configuration file.

## Usage

### 1. Use the TranslatesAttributes trait in your model:

```php
use BamboleeDigital\TranslatableResourceKit\Http\Traits\TranslatesAttributes;
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
use BamboleeDigital\TranslatableResourceKit\Http\Resources\TranslatableResource;

class ProductResource extends TranslatableResource
{
    // You can add custom logic here if needed
}
```

### 3. Create a Collection for your model:

```php
use BamboleeDigital\TranslatableResourceKit\Http\Resources\TranslatableCollection;

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

The middleware is automatically registered by the package in the group specified in the configuration (default is 'api'). You can set the locale by adding a `lang` query parameter to your API requests, e.g., `?lang=pt` for Portuguese.

If you've disabled the automatic middleware registration, you can manually register it using one of these methods:

1. In your `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\Route;
use BamboleeDigital\TranslatableResourceKit\Http\Middleware\SetLocale;

public function boot()
{
    Route::pushMiddlewareToGroup('api', SetLocale::class);
}
```

2. In your `routes/api.php`:

```php
use BamboleeDigital\TranslatableResourceKit\Http\Middleware\SetLocale;

Route::middleware([SetLocale::class])->group(function () {
    // Your API routes here
});
```

3. For Laravel versions with `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'api' => [
        // ...
        \BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale::class,
    ],
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

### Customizing the Query Parameter

If you want to use a different query parameter instead of `lang`, you can modify the `SetLocale` middleware. You'll need to publish the middleware to your application:

```bash
php artisan vendor:publish --provider="BamboleeDigital\TranslatableResourceKit\TranslatableResourceKitServiceProvider" --tag="middleware"
```

Then, edit the published middleware file in `app/Http/Middleware/SetLocale.php` to change the query parameter name.

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