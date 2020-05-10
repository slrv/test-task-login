<?php


namespace Core\DB;


use Core\DB\Operations\Insert;
use Core\DB\Operations\Select;
use Core\DB\Operations\Update;
use Exception;
use Exceptions\Database\ConnectionException;

/**
 * Class DB
 * @package Core\DB
 *
 * NOT IMPLEMENTED
 * update
 * delete
 */
class DB
{
    /**
     * Select data from DB
     *
     * @param string $table
     * @param array $fields
     * @return Select
     * @throws ConnectionException
     */
    public static function select(string $table, array $fields = ['*']): Select {
        $operation = new Select($table);
        $operation->setFields($fields);

        return $operation;
    }

    /**
     * Insert data to DB
     *
     * @param string $table
     * @param $data
     * @return Insert
     * @throws ConnectionException
     */
    public static function insert(string $table, $data): Insert {
        return new Insert($table, $data);
    }

    /**
     * Update data in DB
     *
     * @param string $table
     * @param array $updates
     * @param array $search
     * @return Update
     * @throws ConnectionException
     * @throws Exception
     */
    public static function update(string $table, array $updates, array $search = []): Update {
        $operation = new Update($table, $updates);
        $operation->setWhere($search);

        return $operation;
    }
}