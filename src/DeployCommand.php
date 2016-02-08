<?php namespace EFrane\Deploy;

use EFrane\Deploy\ConditionalProcess\ConditionalProcess;
use EFrane\Deploy\ConditionalProcess\FileExists;
use Illuminate\Console\Command;

/**
 * Deploy Command
 *
 * Runs commands and scripts necessary to put the application in a usable state.
 **/
class DeployCommand extends Command
{
    protected $signature =
        'deploy
        {--update-dependencies : update dependency repositories like npm and run asset pipelines}
        {--fix-missing : fix missing directories and permissions}
        {--optimize : optimize for deployment}';
    protected $description = 'Run commands necessary to put the application in a usable state.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($this->option('update-dependencies')) {
            $this->updateDependencies();
        }

        if ($this->option('fix-missing')) {
            $this->fixMissing();
        }

        if ($this->option('optimize')) {
            $this->call('clear-compiled');
            $this->call('optimize');
        }
    }

    protected function updateDependencies()
    {
        FileExists::setBasePath((function_exists('base_path')) ? base_path() : getcwd());

        $commands = [
            ['npm install', new FileExists('package.json')],
            ['bower install', new FileExists('bower.json')],
            ['gulp --production', new FileExists('gulpfile.js')],
        ];

        foreach ($commands as $command) {
            list($cmd, $condition) = $command;
            $this->conditionalProcess($cmd, $condition);
        }
    }

    protected function conditionalProcess($cmd, $condition)
    {
        $process = new ConditionalProcess($cmd, $condition);
        $output = '';

        if ($process->execute($output)) {
            $this->line($output);
        };
    }

    protected function fixMissing()
    {
        // TODO: fix missing directories and permissions
    }
}
