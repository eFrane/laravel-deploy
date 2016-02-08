[![Build Status](https://travis-ci.org/eFrane/laravel-deploy.svg?branch=master)](https://travis-ci.org/eFrane/laravel-deploy)

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

**`--updateDependencies`**

Updates dependencies and assets using the following systems (only if
a corresponding config file is found):

- npm
- bower
- gulp

**`--fixMissing`**

Fixes missing default directories and permissions for the storage directories.

**`--optimize`**

Combines `clear-compiled` and `optimize`.
