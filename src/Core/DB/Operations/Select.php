<?php


namespace Core\DB\Operations;


use Core\DB\DbOperation;

class Select extends DbOperation
{
    private $fields = ['*'];

    /**
     * Set fields to fetch from database
     *
     * @param string[] $fields
     * @return Select
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    protected function getBaseQuery(): string
    {
        return "select ".implode(',', $this->fields)." from ".$this->table;
    }
}