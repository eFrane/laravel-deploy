<?php namespace EFrane\Deploy\ConditionalProcess;

use Symfony\Component\Process\Process;

class ConditionalProcess
{
    protected $cmd = '';
    protected $condition = null;

    public function __construct($cmd, callable $condition = null)
    {
        $this->cmd = $cmd;

        if (!is_null($condition)) {
            $this->setCondition($condition);
        }
    }

    public function setCondition(callable $condition)
    {
        $this->condition = $condition;
    }

    /**
     * @param $output string|false
     * @return bool
     */
    public function execute(&$output)
    {
        if (!call_user_func($this->condition)) {
            return false;
        }

        $process = new Process($this->cmd);
        if (is_bool($output) && !$output) {
            $process->disableOutput();
        }

        // NOTE: determine if keeping the return value might be useful
        $process->wait();

        $output = $process->getOutput();

        return $process->isSuccessful();
    }
}