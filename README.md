# Laravel Deploy

This package provides the `artisan deploy`-command and aims to simplify the 
deployment process of Laravel 5 applications.

## Installation

`laravel-deploy` is available as a composer package, you can thus install 
it with `composer require efrane/laravel-deploy`.

As this is a Laravel package, it will only make sense in a Laravel
application. Also, don't forget to add the service provider to your
`app.php` config:

```php
  $providers = [
      ...
      
      EFrane\Deploy\DeployServiceProvider::class,
  ];
```

## Usage

This package registers the `./artisan deploy` command. The default configuration
only runs the `--optimize`-option as described in the Options section below.

Default options can be changed in the `laraveldeploy.php` config file which
is providable via `./artisan vendor:publish`. If so desired, additional
console commands can be added to the deploy process by simply adding their
command line call strings (see [`Command::call()`]()) to the `additonalCommands`
config option like so:

```
<?php return [
  // defaults...
  
  'additionalCommands' => [
    'migrate' // this would run artisan:migrate after all other deploy commands
  ]
];
```

Any default can be overriden by calling the appropriate `-no-$option` option on
the command line, i.e. `./artisan --no-fix-missing` would jump over fixing 
directories and permissions.

## Options

**`--[no-]-update-dependencies`**

Updates dependencies and assets using the following systems (only if
a corresponding config file is found):

- npm
- bower
- gulp

**`--[no-]-fix-missing`**

Fixes missing default directories and permissions for the storage directories.

**`--[no-]-optimize`**

Combines `clear-compiled` and `optimize`.

## License

This package is released under the terms of the MIT license.

## Disclaimer on contributions

I am mainly developing this package for my own Laravel deployments, thus
the feature set is very biased. However, if you find yourself needing
a feature, please do not hesitate to ask by creating an issue or even implement
it and sending a pull request.

### Testing

Unfortunately, I currently have no clue how to test this functionality other
than using it in a laravel project. Thus, contributions in terms of making
this testable are especially welcome.
