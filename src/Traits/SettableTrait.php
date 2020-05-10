<?php


namespace Traits;

use Exception;

/**
 * Trait SettableTrait
 * @package Traits
 *
 * This trait add functional to add some options which should be set (fill model, setup validation class etc.)
 * Add $optionsList member as an array of strings with key names of set fields.
 *
 * @property-read array $optionsList An array of available key names
 */
trait SettableTrait
{
    /**
     * Options array
     *
     * @var array
     */
    private $options = [];

    /**
     * Setup instance
     *
     * @param array $options
     * @param bool $fillMissedNull
     * @return $this
     */
    public function setOptions(array $options, bool $fillMissedNull = false)
    {
        if ($this->optionsList && is_array($this->optionsList)) {
            foreach ($this->optionsList as $field) {
                if (isset($options[$field])) {
                    $this->options[$field] = $options[$field];
                } elseif ($fillMissedNull) {
                    $this->options[$field] = null;
                }
            }
        }

        return $this;
    }

    /**
     * Return options
     *
     * @return array
     */
    public function getOptions(): array {
        return $this->options ?? [];
    }

    /**
     * Check if option exists
     *
     * @param string $field
     * @return bool
     */
    public function hasOption(string $field): bool {
        return isset($this->getOptions()[$field]);
    }

    /**
     * Get option value by field name
     *
     * @param string $field
     * @return mixed
     */
    public function getOptionValue(string $field) {
        return $this->getOptions()[$field];
    }

    /**
     * Update option value
     *
     * @param string $field
     * @param $value
     * @return $this
     */
    public function updateOptionValue(string $field, $value) {
        $this->options[$field] = $value;

        return $this;
    }

    /**
     * Get value from property
     *
     * @param $name
     * @return mixed|null
     * @throws Exception
     */
    public function __get($name)
    {
        if (!in_array($name, $this->optionsList)) {
            throw new Exception("Property $name is not available");
        }

        return $this->hasOption($name) ? $this->getOptionValue($name) : null;
    }
}