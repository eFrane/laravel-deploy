<?php namespace EFrane\Deploy;

use Illuminate\Console\Command;

/**
 * Deploy Command
 *
 * Runs commands and scripts necessary to put the application in a usable state.
 **/
class DeployCommand extends Command
{
    protected $signature = 'deploy {--update-dependencies}';
    protected $description = 'Run commands necessary to put the application in a usable state.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($this->option('update-dependencies')) {
            $this->execIf('npm install', function () { 
                return file_exists(base_path('package.json')); 
            });

            $this->execIf('bower install', function () { 
                return file_exists(base_path('bower.json')); 
            });

            $this->execIf('gulp --production', function () { 
                return file_exists(base_path('gulpfile.js')); 
            });
        }

        $this->call('clear-compiled');
        $this->call('optimize');
    }

    protected function execIf($cmd, \Closure $condition) {
        if ($condition()) 
            $this->execShellCmd($cmd);
    }

    protected function execShellCmd($cmd)
    {
        exec($cmd, $output);
        $this->output($output);
    }

    protected function output($output)
    {
        $this->line(implode("\n", $output));
    }
}
