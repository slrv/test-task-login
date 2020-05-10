<?php


namespace Exceptions\Database;


use Core\Interfaces\IHasErrorToResponse;
use Exception;

class ExecutionException extends Exception implements IHasErrorToResponse
{
    private $statement;

    function __construct($statement)
    {
        parent::__construct($this->statement->error, 422);
        $this->statement = $statement;
    }

    /**
     * @return mixed
     */
    public function getStatement()
    {
        return $this->statement;
    }

    function getErrorToResponse()
    {
        return $this->statement->error;
    }
}