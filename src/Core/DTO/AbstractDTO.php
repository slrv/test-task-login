<?php


namespace Core\DTO;


use Core\Sanitization\ISanitized;
use Core\Validation\Validator;
use Exception;
use Exceptions\DTO\WrongPropertySchemaException;
use Exceptions\Validation\IncorrectValidationInstanceException;
use Exceptions\Validation\ValidationException;
use Exceptions\Validation\ValidationNameException;
use Traits\SettableTrait;

/**
 * Class AbstractDTO
 * @package Core\DTO
 * Describe common logic for DTO in request
 */
abstract class AbstractDTO
{
    use SettableTrait;

    /**
     * AbstractDTO constructor.
     * @param array $data
     */
    function __construct(array $data) {
        $this->optionsList = array_map(function (PropertySchema $propertySchema) {
            return $propertySchema->getName();
        }, $this->getSchema());

        $this->setOptions($data);
    }

    /**
     * Validate data
     *
     * @throws WrongPropertySchemaException
     * @throws IncorrectValidationInstanceException
     * @throws ValidationException
     * @throws ValidationNameException
     */
    public function validate() {
        $validationResult = Validator::validate($this->getOptions(), $this->getSchema());

        if (!$validationResult->isValid()) {
            throw new ValidationException($validationResult->getErrors());
        }
    }

    /**
     * Sanitize input data
     *
     * @throws Exception
     */
    public function sanitize() {
        foreach ($this->getSchema() as $propertySchema) {
            $name = $propertySchema->getName();
            if ($this->hasOption($name) && !!$sanitizer = $propertySchema->getSanitizer()) {
                $rawValue = $this->getOptionValue($name);
                if (is_callable($sanitizer)) {
                    $result = $sanitizer($rawValue);
                } elseIf (is_object($sanitizer) && ($sanitizer instanceof ISanitized)) {
                    $result = $sanitizer->sanitize($rawValue);
                } else {
                    throw new Exception("Wrong sanitizer for $name property");
                }

                $this->updateOptionValue($name, $result);
            }
        }
    }

    /**
     * Return array of PropertySchema for field in DTO
     *
     * @return PropertySchema[]
     */
    abstract function getSchema(): array;
}