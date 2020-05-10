<?php


namespace Core\Validation\Validators;


use Core\Validation\AbstractValidator;
use Traits\SettableTrait;

class StringValidator extends AbstractValidator
{
    use SettableTrait;

    protected $optionsList = ['minLength', 'maxLength'];
    protected $name = 'string';

    function validate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        if ($this->hasOption('maxLength')) {
            if (strlen($value) > $this->getOptionValue('maxLength')) {
                $this->setError('maxLength');
                return false;
            }
        }

        if ($this->hasOption('minLength')) {
            if (strlen($value) < $this->getOptionValue('minLength')) {
                $this->setError('minLength');
                return false;
            }
        }

        return true;
    }
}