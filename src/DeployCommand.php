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
            $this->execShellCmd('npm install');
            $this->execShellCmd('gulp --production');
        }

        $this->call('clear-compiled');
        $this->call('optimize');
    }

    protected function execShellCmd($cmd)
    {
        exec($cmd, $output);
        $this->output($output);
    }

    protected function output($output)
    {
        if (!$this->option('silent')) {
            $this->line(implode("\n", $output));
        }
    }
}
