<?php

namespace EFrane\Deploy;

use Illuminate\Support\ServiceProvider;

class DeployServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([DeployCommand::class]);
    }
}
