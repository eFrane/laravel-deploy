<?php namespace EFrane\Deploy\ConditionalProcess;

class FileExists extends Conditional
{
    /**
     * @var string
     */
    protected $filename = '';

    /**
     * @var string
     */
    protected static $basePath = '';

    /**
     * FileExists constructor.
     * @param $filename string
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param $basePath string
     */
    public static function setBasePath($basePath)
    {
        static::$basePath = $basePath;
    }

    protected function execute()
    {
        if (strlen($this->filename) == 0) {
            return false;
        }

        $filename = $this->filename;
        if (strlen(static::$basePath) > 0) {
            $filename = (substr(static::$basePath, -1) == DIRECTORY_SEPARATOR)
                ? static::$basePath . $filename
                : static::$basePath . DIRECTORY_SEPARATOR . $filename;
        }

        return file_exists($filename);
    }
}