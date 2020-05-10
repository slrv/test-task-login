<?php


namespace Core\Validation;


use Exceptions\Validation\ValidationNameException;
use Traits\SettableTrait;

abstract class AbstractValidator
{
    /**
     * @var string
     */
    private $error = 'invalid';

    /**
     * Unique name of validator
     * Should be set in concrete class
     *
     * @var string
     */
    protected $name;

    function __construct(array $options = [])
    {
        if (in_array(SettableTrait::class, class_uses($this))) {
            $this->setOptions($options);
        }
    }

    /**
     * Return error of validation check
     *
     * @return mixed
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Set key of error
     * Should be used in validate method
     *
     * @param string $error
     * @return $this
     */
    public function setError(string $error) {
        $this->error = $error;

        return $this;
    }

    /**
     * Return name of validator type
     *
     * @return string
     * @throws ValidationNameException
     */
    public function getName() {
        if (!$this->name || !is_string($this->name)) {
            throw new ValidationNameException();
        }

        return $this->name;
    }

    /**
     * Validate input value. Must return boolean result of validation check.
     * Also $this->error property can be set here for clarification of error
     *
     * @param $value
     * @return bool
     */
    abstract function validate($value): bool;
}