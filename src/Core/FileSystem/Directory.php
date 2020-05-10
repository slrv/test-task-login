<?php


namespace Core\FileSystem;


use Core\Config;

class Directory
{
    /**
     * Recursively create directory
     *
     * @param string $name
     * @return bool
     */
    public static function create(string $name): bool {
        return mkdir(self::getFullPathDir($name), 0777, true);
    }

    /**
     * Return base file directory
     *
     * @return string
     */
    public static function getBaseDir(): string {
        return Config::getValue('BASE_STORAGE_DIR').'/';
    }

    /**
     * Return full path to dir
     *
     * @param string $name
     * @return string
     */
    public static function getFullPathDir(string $name = ''): string {
        $path = self::getBaseDir().$name;
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        return $path;
    }

    /**
     * Check if provided path is directory
     *
     * @param string $name
     * @return bool
     */
    public static function isDir(string $name): bool {
        return is_dir(self::getFullPathDir($name));
    }

    /**
     * Check if exists and create directory if not
     *
     * @param string $name
     * @return string
     */
    public static function prepareDir(string $name): string {
        if (self::isDir($name) || self::create($name)) {
            return self::getFullPathDir($name);
        }

        return false;
    }
}