<?php


namespace Exceptions\Validation;

use Exception;

class ValidationNameException extends Exception
{
    public function __construct()
    {
        parent::__construct('Validation name should be set properly');
    }
}