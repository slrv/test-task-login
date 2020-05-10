<?php


namespace Core\DB\Operations;


use Core\DB\DbOperation;

class Insert extends DbOperation
{
    private $data;

    public function __construct(string $table, $data = [])
    {
        parent::__construct($table);
        $this->data = $data;
    }

    /**
     * Set data to insert
     *
     * @param mixed $data
     * @return Insert
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    protected function getBaseQuery(): string
    {
        $this->bindingParams = [];

        $fields = array_keys($this->data[0]);
        $fieldsStr = implode(',', $fields);

        foreach ($this->data as $row) {
            foreach ($fields as $field) {
                $this->bindingParams[] = $row[$field];
            }
        }

        $valuesRowPlaceholders = '('.implode(
            ',', array_fill(0, count($fields), '?')
         ).')';

        $totalValuesPlaceholders = implode(
            ',', array_fill(0, count($this->data), $valuesRowPlaceholders)
        );

        return "insert into $this->table ($fieldsStr) values $totalValuesPlaceholders";
    }
}