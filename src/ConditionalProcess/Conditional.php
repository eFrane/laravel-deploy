<?php namespace EFrane\Deploy\ConditionalProcess;

abstract class Conditional
{
    /**
     * @return bool
     */
    abstract protected function execute();

    public function __invoke()
    {
        return $this->execute();
    }
}