<?php

namespace EFrane\Deploy;

use EFrane\ConditionalProcess\ConditionalProcess;
use EFrane\ConditionalProcess\Conditionals\FileExists;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Deploy Command.
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

    protected $verbosity = 0;

    /**
     * Execute the console command.
     *
     * @param ConfigRepository $config Laravel configuration repository
     * @return void
     */
    public function fire(ConfigRepository $config)
    {
        if ($this->option('update-dependencies') || $config->get('laraveldeploy.updateDependencies')) {
            $this->updateDependencies();
        }

        if ($this->option('fix-missing') || $config->get('laraveldeploy.fixMissing')) {
            $this->fixMissing();
        }

        if ($this->option('optimize') || $config->get('laraveldeploy.optimize')) {
            $this->call('clear-compiled');
            $this->call('optimize');
        }

        if (is_array($config->get('laraveldeploy.additionalCommands'))) {
            $this->callAdditionalCommands($config);
        }
    }

    protected function updateDependencies()
    {
        $this->info('Updating dependencies and running asset pipelines...');

        FileExists::setBasePath((function_exists('base_path')) ? base_path() : getcwd());

        $commands = [
            ['npm install', new FileExists('package.json')],
            ['bower install', new FileExists('bower.json')],
            ['gulp --production', new FileExists('gulpfile.js')],
        ];

        foreach ($commands as $command) {
            list($cmd, $condition) = $command;
            $this->conditionalProcess($cmd, $condition, 0);
        }
    }

    protected function conditionalProcess($cmd, $condition, $timeout)
    {
        $process = new ConditionalProcess($cmd, $condition);
        $process->setTimeout($timeout);

        $output = '';

        $this->line("Running `{$cmd}`...");
        try {
            if ($process->execute($output) && $this->verbosity == OutputInterface::VERBOSITY_VERBOSE) {
                $this->line($output);
            } else {
                $this->info('Success!');
            }
        } catch (ProcessFailedException $e) {
            $this->error('Failed:');
            $this->line($output);
        }
    }

    protected function fixMissing()
    {
        $this->info('Validating required directories...');

        $dirs = new Collection([
            'bootstrap/cache',
            'storage/app',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/logs',
        ]);

        $perms = 0755;

        $dirs->each(function ($dir) use ($perms) {
            $this->line("Creating/checking {$dir}...");

            if (!is_dir($dir)) {
                mkdir($dir, $perms, true);
            }
        });
    }

    protected function callAdditionalCommands(ConfigRepository $config)
    {
        /* @var $additionalCommands \Illuminate\Support\Collection */
        $additionalCommands = collect($config->get('laraveldeploy.additionalCommands'));

        $additionalCommands->map(function ($commandString) {
            try {
                $this->callSilent($commandString);
                $this->info('Successfully called `' . $commandString . '`');
            } catch (\Exception $e) {
                $this->error('Failed calling `' . $commandString . '`');
            }
        });
    }
}
