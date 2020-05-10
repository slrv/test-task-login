<?php


namespace Exceptions\DTO;

use Exception;

class WrongPropertySchemaException extends Exception
{
    private $schema;

    public function __construct($schema)
    {
        parent::__construct('Incorrect property schema');
        $this->schema = $schema;
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }
}