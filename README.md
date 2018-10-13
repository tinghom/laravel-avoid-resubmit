# Laravel-avoid-resubmit
A package that is applied to Laravel to avoid resubmitting form data

# Install
Use composer to install package
```shell
$ composer required tinghom/laravel-avoid-resubmit
```

*notice* if your Laravel version is beyond 5.6, package’ll auto register ServiceProvider to app.php.
But if your version is above 5.6, you’ve to register by yourself.

config/app.php
```php
'provider' => [
	// package ServiceProvider
	Tinghom\Middleware\AvoidResubmitServiceProvider::class,
]
```
