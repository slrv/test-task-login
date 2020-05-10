<?php


namespace Exceptions\Validation;


use Core\Interfaces\IHasErrorToResponse;
use Exception;

class ValidationException extends Exception implements IHasErrorToResponse
{
    protected $errors;

    function __construct(array $errors)
    {
        parent::__construct('Validation error', 400);
        $this->errors = $errors;
    }

    function getErrorToResponse()
    {
        return $this->errors;
    }
}