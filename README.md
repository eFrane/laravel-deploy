# Laravel Deploy

This package provides the `artisan deploy`-command and aims to simplify the 
deployment process of Laravel 5 applications.

## Installation

`laravel-deploy` is available as a composer package, you can thus install 
it with `composer require efrane/laravel-deploy`.

As this is a Laravel package, this will only make sense in a Laravel
application. Also, don't forget to add the service provider to your
`app.php`:

```php
  $providers = [
      ...
      
      EFrane\Deploy\DeployServiceProvider::class,
  ];
```

## Options

**`--update-dependencies`**

Updates dependencies and assets using the following systems (only if
a corresponding config file is found):

- npm
- bower
- gulp

**`--fix-missing`**

Fixes missing default directories and permissions for the storage directories.

**`--optimize`**

Combines `clear-compiled` and `optimize`.

## License

This package is released under the terms of the MIT license.


