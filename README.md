## Middleware

This package includes an optional `SetLocale` middleware that can be used to set the application locale based on a query parameter. To use it:

1. Register the middleware in your `app/Http/Kernel.php` file:

```php
protected $routeMiddleware = [
    // ...
    'set-locale' => \BamboleeDigital\TranslatableResourceKit\Middleware\SetLocale::class,
];
```

2. Apply the middleware to your routes or route groups:

```php
Route::group(['middleware' => 'set-locale'], function () {
    // Your routes here
});
```

3. Configure supported locales in your `config/translatable-resource-kit.php`:

```php
return [
    // ...
    'supported_locales' => ['en', 'es', 'fr'], // Add or modify as needed
];
```

Now, you can set the locale by adding a `lang` query parameter to your requests, e.g., `?lang=es`.# translatable-resource-kit
