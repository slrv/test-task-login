<?php


namespace Exceptions\Database;


use Exception;
use mysqli;

class ConnectionException extends Exception
{
    /**
     * @var mysqli
     */
    private $connection;

    public function __construct(mysqli $connection, $code = 0)
    {
        parent::__construct($connection->connect_error, $code);

        $this->connection = $connection;
    }

    /**
     * @return mysqli
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}