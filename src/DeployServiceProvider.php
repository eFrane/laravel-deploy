<?php

namespace EFrane\Deploy;

use Illuminate\Support\ServiceProvider;

class DeployServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laraveldeploy.php', 'laraveldeploy');
    }

    public function register()
    {
        $this->commands([DeployCommand::class]);
        $this->publishes(__DIR__.'/../config/laraveldeploy.php', 'config');
    }
}
