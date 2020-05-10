<?php


namespace Core;


class Session
{
    /**
     * SessionModel data storage
     *
     * @var array
     */
    private static $sessionData;

    /**
     * Set session key value
     *
     * @param string $key
     * @param $value
     */
    public static function setValue(string $key, $value) {
        self::$sessionData[$key] = $value;
    }

    /**
     * Check if session storage has key
     *
     * @param string $key
     * @return bool
     */
    public static function hasKey(string $key) {
        return isset(self::$sessionData[$key]);
    }

    /**
     * Get session storage value
     *
     * @param string $key
     * @return mixed
     */
    public static function getValue(string $key) {
        return self::$sessionData[$key];
    }
}