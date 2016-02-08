<?php

use EFrane\Deploy\ConditionalProcess\ConditionalProcess;
use EFrane\Deploy\ConditionalProcess\FileExists;

class ConditionalProcessTest extends PHPUnit_Framework_TestCase
{
    public function testExecutesWithConditional()
    {
        $cmd = 'echo "Hello World"';
        $condition = new FileExists('README.md');

        $process = new ConditionalProcess($cmd, $condition);

        $actual = '';
        $expected = $this->getExecutesOutput();

        $this->assertTrue($process->execute($actual));
        $this->assertEquals($expected, $actual);
    }

    public function testExecutesWithClosure()
    {
        $cmd = 'echo "Hello World"';
        $condition = function () { return true; };

        $process = new ConditionalProcess($cmd, $condition);

        $actual = '';
        $expected = $this->getExecutesOutput();

        $this->assertTrue($process->execute($actual));
        $this->assertEquals($expected, $actual);
    }

    public function testDisabledOutput()
    {
        $this->markTestIncomplete('Not yet implemented.');
    }

    public function getExecutesOutput()
    {
        return "Hello World\n";
    }

    public function testSetGetCondition()
    {
        $condition = function () { return true; };

        $process = new ConditionalProcess('');

        $process->setCondition($condition);
        $this->assertEquals($condition, $process->getCondition());
    }

    public function testFailsCondition()
    {
        $condition = function () { return false; };

        $process = new ConditionalProcess('', $condition);

        $this->assertFalse($process->execute($output));
    }

    public function testFailsProcess()
    {
        $this->markTestIncomplete('Not yet implemented.');
    }
}
