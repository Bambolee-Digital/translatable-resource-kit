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

git
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

[Seções 1-4 permanecem inalteradas]

### 5. Using the middleware:

The middleware is automatically registered by the package in the group specified in the configuration (default is 'api'). You can set the locale by adding a `lang` query parameter to your API requests, e.g., `?lang=pt` for Portuguese.

If you've disabled the automatic middleware registration, you can manually register it using one of these methods:

1. In your `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\Route;
use BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale;

public function boot()
{
    Route::pushMiddlewareToGroup('api', SetLocale::class);
}
```

2. In your `routes/api.php`:

```php
use BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale;

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

[Esta seção permanece inalterada]

## Advanced Usage

[Esta seção permanece inalterada]

### Customizing the Query Parameter

If you want to use a different query parameter instead of `lang`, you can modify the `SetLocale` middleware. You'll need to publish the middleware to your application:

```bash
php artisan vendor:publish --provider="BamboleeDigital\TranslatableResourceKit\TranslatableResourceKitServiceProvider" --tag="middleware"
```

Then, edit the published middleware file in `app/Http/Middleware/SetLocale.php` to change the query parameter name.

[As seções restantes permanecem inalteradas]