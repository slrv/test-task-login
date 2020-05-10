<?php


namespace Core\FileSystem;


class File
{
    /**
     * Add row to file
     *
     * @param string $filename
     * @param string[]|string $data
     * @param bool $rewrite
     */
    public static function writeToFile(string $filename, $data, bool $rewrite = false) {
        $openFlag = $rewrite ? 'w' : 'a';
        $file = fopen($filename, $openFlag);

        if (is_array($data)) {
            foreach ($data as $string) {
                fwrite($file, $string.PHP_EOL);
            }
        } else {
            fwrite($file, $data.PHP_EOL);
        }

        fclose($file);
    }
}