<?php


namespace Core\Validation\Validators;


use Core\Validation\AbstractValidator;
use Traits\SettableTrait;

class EmailValidator extends AbstractValidator
{
    use SettableTrait;

    protected $optionsList = ['maxLength'];
    protected $name = 'email';

    function validate($value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->hasOption('maxLength')) {
            if (strlen($value) > $this->getOptionValue('maxLength')) {
                $this->setError('maxLength');
                return false;
            }
        }

        return true;
    }
}