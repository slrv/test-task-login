<?php


namespace Core\Validation\Validators;


use Core\Validation\AbstractValidator;
use Traits\SettableTrait;

class FileValidator extends AbstractValidator
{
    use SettableTrait;

    protected $optionsList = ['type', 'maxSize'];
    protected $name = 'file';

    function validate($value): bool
    {
        /**
         * Check success upload
         */
        if (!is_uploaded_file($value['tmp_name'])) {
            return false;
        }

        if ($value['error']) {
            $this->setError('error-'.$value['error']);
            return false;
        }

        /**
         * Check max size
         */
        if ($this->hasOption('maxSize')) {
            if ($value['size'] > $this->getOptionValue('maxSize')) {
                $this->setError('maxSize');
                return false;
            }
        }

        /**
         * Check type
         */
        if ($this->hasOption('type')) {
            if (!in_array($value['type'], $this->getOptionValue('type'))) {
                $this->setError('type');
                return false;
            }
        }

        return true;
    }
}