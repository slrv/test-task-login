<?php


namespace Core\Validation;


use Core\DTO\PropertySchema;
use Exceptions\DTO\WrongPropertySchemaException;
use Exceptions\Validation\IncorrectValidationInstanceException;
use Exceptions\Validation\ValidationNameException;
use Traits\SettableTrait;

class Validator
{
    /**
     * Stored errors during validation
     *
     * @var array
     */
    private $errors = [];

    /**
     * @param array $data
     * @param array $schema
     * @return Validator
     * @throws WrongPropertySchemaException
     * @throws IncorrectValidationInstanceException
     * @throws ValidationNameException
     */
    public static function validate(array $data, array $schema): Validator
    {
        $validator = new Validator();

        foreach ($schema as $propertySchema) {
            if (!($propertySchema instanceof PropertySchema)) {
                throw new WrongPropertySchemaException($propertySchema);
            }

            $validator->validatePropertyInArray($data, $propertySchema);
        }

        return $validator;
    }

    private function __construct()
    {
    }

    /**
     * Returns if validation check success
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    /**
     * Returns validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $data
     * @param PropertySchema $schema
     * @throws IncorrectValidationInstanceException
     * @throws ValidationNameException
     */
    private function validatePropertyInArray(array $data, PropertySchema $schema)
    {
        $name = $schema->getName();
        $validatorName = null;
        $error = null;
        $options = null;

        if (isset($data[$name])) {
            $value = $data[$name];
            $validators = $schema->getValidators();
            while (!$error && count($validators) != 0) {
                $concreteValidator = array_shift($validators);
                if (!($concreteValidator instanceof AbstractValidator)) {
                    throw new IncorrectValidationInstanceException($concreteValidator);
                }
                if (!$concreteValidator->validate($value)) {
                    $error = $concreteValidator->getError();
                    $validatorName = $concreteValidator->getName();
                    if (in_array(SettableTrait::class, class_uses($concreteValidator))) {
                        /** @var SettableTrait $concreteValidator */
                        $options = $concreteValidator->getOptions();
                    }
                }
            }
        } elseif ($schema->isRequired()) {
            $error = 'required';
            $validatorName = 'required';
        }

        if (!!$error) {
            $this->errors[$name] = [
                'validator' => $validatorName,
                'error' => $error,
                'options' => $options
            ];
        }
    }
}