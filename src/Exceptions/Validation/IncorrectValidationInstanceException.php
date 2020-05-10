<?php


namespace Exceptions\Validation;


use Exception;

class IncorrectValidationInstanceException extends Exception
{
    private $validator;

    public function __construct($validator)
    {
        parent::__construct('Validator class must extends AbstractValidator');

        $this->validator = $validator;
    }

    /**
     * @return mixed
     */
    public function getValidator()
    {
        return $this->validator;
    }
}