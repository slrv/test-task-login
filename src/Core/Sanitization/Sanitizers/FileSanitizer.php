<?php


namespace Core\Sanitization\Sanitizers;


use Core\FileSystem\Directory;
use Core\Sanitization\ISanitized;
use Exception;

class FileSanitizer implements ISanitized
{
    /**
     * Function to generate filename
     *
     * @var Callable
     */
    private $filenameFn;

    /**
     * Directory to save file
     *
     * @var string
     */
    private $dir;

    public function __construct(Callable $filenameFn, string $dir = null)
    {
        $this->filenameFn = $filenameFn;
        $this->dir = $dir;
    }

    /**
     * @param $value
     * @return mixed
     * @throws Exception
     */
    function sanitize($value)
    {
        $filenameFn = $this->filenameFn;
        $filename = $filenameFn($value);
        $baseDir = Directory::prepareDir($this->dir);

        if (move_uploaded_file ($value['tmp_name'], $baseDir.$filename)) {
            return $filename;
        }

        throw new Exception('Upload file error', 422);
    }
}