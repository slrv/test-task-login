<?php


namespace Core\DB\Operations;


use Core\DB\DbOperation;

class Update extends DbOperation
{
    /**
     * An array to update
     *
     * @var array
     */
    private $update;

    public function __construct(string $table, array $update)
    {
        parent::__construct($table);
        $this->update = $update;
    }

    protected function getBaseQuery(): string
    {
        $this->bindingParams = [];
        $updates = [];
        foreach ($this->update as $key => $value) {
            $updates[] = "$key = ?";
            $this->bindingParams[] = $value;
        }

        $updateStr = implode(',', $updates);

        return "update $this->table set $updateStr";
    }
}