<?php namespace EFrane\Deploy;

use EFrane\ConditionalProcess\ConditionalProcess;
use EFrane\ConditionalProcess\FileExists;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        $this->info('Updating dependencies and running asset pipelines...');

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

        $this->line("Running `{$cmd}`...");
        try {
            if ($process->execute($output) && $this->verbosity == OutputInterface::VERBOSITY_VERBOSE) {
                $this->line($output);
            } else {
                $this->info('Success!');
            }
        } catch (ProcessFailedException $e) {
            $this->error("Failed:");
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

        $perms = 0x775;

        $dirs->each(function ($dir) use ($perms) {
            $this->line("Creating/checking {$dir}...");

            if (!is_dir($dir)) {
                mkdir($dir, $perms, true);
            }
        });
    }
}
