<?php


namespace Core\DTO;


use Core\Sanitization\ISanitized;
use Core\Validation\AbstractValidator;
use Traits\SettableTrait;

class PropertySchema
{
    /**
     * Name of property
     *
     * @var string
     */
    private $name;

    /**
     * Required flag
     *
     * @var bool
     */
    private $required;

    /**
     * An array of validators
     *
     * @var array
     * @example
     * $emailOptions = ['maxLength' => 50];
     * [
     *      [EmailValidator::class, $emailOptions]
     * ]
     *
     * $emailOptions - optional. Associative array, where key is settable property name
     * Applied only for @see SettableTrait validator
     */
    private $validators;

    /**
     * Value sanitizer
     *
     * @var ISanitized|Callable|null
     */
    private $sanitizer;

    /**
     * PropertySchema constructor.
     * @param string $name
     * @param bool $required
     * @param array $validators
     * @param ISanitized|Callable $sanitizer
     */
    function __construct(string $name,  bool $required = true, array $validators = [], $sanitizer = null) {
        $this->name = $name;
        $this->required = $required;
        $this->validators = $validators;
        $this->sanitizer = $sanitizer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     * @return PropertySchema
     */
    public function setRequired(bool $required): PropertySchema
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return array
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * @param array $validators
     * @return PropertySchema
     */
    public function setValidators(array $validators): PropertySchema
    {
        $this->validators = $validators;

        return $this;
    }

    /**
     * Add validator to the end of chain
     *
     * @param AbstractValidator $validator
     */
    public function addValidator(AbstractValidator $validator) {
        $this->validators[] = $validator;
    }

    /**
     * @return Callable|ISanitized|null
     */
    public function getSanitizer()
    {
        return $this->sanitizer;
    }

    /**
     * @param Callable|ISanitized|null $sanitizer
     * @return PropertySchema
     */
    public function setSanitizer($sanitizer): PropertySchema
    {
        $this->sanitizer = $sanitizer;

        return $this;
    }
}