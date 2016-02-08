<?php

use EFrane\Deploy\ConditionalProcess\Conditional;

class ConditionalTest extends PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        /* @var $stub Conditional|PHPUnit_Framework_MockObject_MockObject */
        $stub = $this->getMock(Conditional::class, ['execute']);
        $stub->method('execute')->willReturn(true);
        $stub->expects($this->once())->method('execute');

        $stub();
    }
}
