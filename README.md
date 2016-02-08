[![Build Status](https://travis-ci.org/eFrane/laravel-deploy.svg?branch=master)](https://travis-ci.org/eFrane/laravel-deploy)

# Laravel Deploy

This package provides the `artisan deploy` command, which simplifies the
commands that need to be run to make Laravel applications deployable
down to a single one.

Internally, it always runs `clear-compiled` and `optimize`. If invoked
as `php artisan deploy --update-dependencies` it additionally will update the
required npm dependencies and run `gulp`.

