<?php


namespace Core\DB;

use Core\Config;
use Exceptions\Database\ConnectionException;
use mysqli;

class DbConnection
{
    /**
     * Instance of connection
     * @var mysqli
     */
    private static $connection;

    private function __construct()
    {
    }

    /**
     * Get MySQL connection
     *
     * @return mysqli
     * @throws ConnectionException
     */
    public static function getConnection(): mysqli
    {
        if (!self::$connection) {
            self::createConnection();
        }

        return self::$connection;
    }

    /**
     * Create connection
     *
     * @throws ConnectionException
     */
    private static function createConnection()
    {
        self::$connection = new mysqli(
            Config::getValue('DB_HOST', 'localhost'),
            Config::getValue('DB_USER'),
            Config::getValue('DB_PASSWORD'),
            Config::getValue('DB_NAME')
        );

        if (self::$connection->connect_errno) {
            throw new ConnectionException(self::$connection);
        }
    }
}