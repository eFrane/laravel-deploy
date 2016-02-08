<?php

use EFrane\Deploy\ConditionalProcess\FileExists;

class FileExistsTest extends PHPUnit_Framework_TestCase
{
    public function testFileExistsDefault()
    {
        $fe = new FileExists('README.md');
        $this->assertTrue($fe());
    }

    public function testFileMissingDefault()
    {
        $fe = new FileExists('_i_don_t_exist');
        $this->assertFalse($fe());
    }

    public function testFileExistsChangeBase()
    {
        FileExists::setBasePath('src');

        $fe = new FileExists('DeployCommand.php');
        $this->assertTrue($fe());

        FileExists::resetBasePath();
    }

    public function testFileMissingChangeBase()
    {
        FileExists::setBasePath('src');

        $fe = new FileExists('_i_don_t_exist');
        $this->assertFalse($fe());

        FileExists::resetBasePath();
    }
}
