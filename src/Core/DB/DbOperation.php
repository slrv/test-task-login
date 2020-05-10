<?php


namespace Core\DB;


use Exception;
use Exceptions\Database\ConnectionException;
use Exceptions\Database\ExecutionException;
use mysqli;

/**
 * Class DbOperation
 * @package Core\DB
 *
 * NOT IMPLEMENTED
 * having
 *
 * NOT FULLY IMPLEMENTED
 * joins
 */
abstract class DbOperation
{
    /**
     * Database connection
     *
     * @var mysqli
     */
    private $conn;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table;

    /**
     * An array of where clauses
     *
     * @var array
     */
    protected $whereClauses = [];

    /**
     * An array of order statements
     *
     * @var array
     */
    protected $orderStatements = [];

    /**
     * An array of group by statements
     *
     * @var array
     */
    protected $groupByStatements = [];

    /**
     * Result rows limit
     *
     * @var int
     */
    protected $limitValue;

    /**
     * Result rows offset
     *
     * @var int
     */
    protected $offsetValue;

    /**
     * An array of joins
     *
     * @var array
     */
    protected $joins = [];

    /**
     * SQL statement
     *
     * @var string
     */
    private $query;

    /**
     * An array of binding params
     *
     * @var array
     */
    protected $bindingParams = [];

    /**
     * DbOperation constructor.
     * @param string $table
     * @throws ConnectionException
     */
    public function __construct(string $table)
    {
        $this->table = $table;
        $this->conn = DbConnection::getConnection();
    }

    /**
     * Add join
     *
     * @param string $table
     * @param string $mainTableCol
     * @param string $joinTableCol
     * @return DbOperation
     */
    public function join(string $table, string $mainTableCol, string $joinTableCol): self {
        $this->joins[] = [$table, $mainTableCol, $joinTableCol];

        return $this;
    }

    /**
     * Set joins
     *
     * @param array $joins
     * @return DbOperation
     */
    public function setJoins(array $joins): self {
        foreach ($joins as $join) {
            $this->join($join[0], $join[1], $join[2]);
        }

        return $this;
    }

    /**
     * Add where clause
     *
     * @Example
     * $this->where('email', '=', 'asd@asd.asd');
     *
     * @param string $field
     * @param string $comparatorFn '=', '>' ...
     * @param $value
     * @return DbOperation
     */
    public function where(string $field, string $comparatorFn, $value): self
    {
        $this->whereClauses[] = [$field, $comparatorFn, $value];

        return $this;
    }

    /**
     * Set where clauses
     *
     * @param array $clauses
     * @return DbOperation
     * @throws Exception
     */
    public function setWhere(array $clauses): self {
        foreach ($clauses as $clause) {
            $field = $clause[0];

            switch (count($clause)) {
                case 2:
                    $comparatorFn = '=';
                    $value = $clause[1];
                    break;

                case 3:
                    $comparatorFn = $clause[1];
                    $value = $clause[2];
                    break;

                default:
                    throw new Exception("Incorrect number (".count($clauses).") of where clause parameters");
            }

            $this->where($field, $comparatorFn, $value);
        }

        return $this;
    }

    /**
     * Add new order expression
     *
     * @param string $value Column name or SQL valid order expression
     * @return DbOperation
     */
    public function order(string $value): self
    {
        $this->orderStatements[] = $value;

        return $this;
    }

    /**
     * Set order statements
     *
     * @param array $orders
     * @return $this
     */
    public function setOrder(array $orders): self {
        foreach ($orders as $order) {
            $this->order($order);
        }

        return $this;
    }

    /**
     * Add new order expression
     *
     * @param string $value Column name or SQL valid group by expression
     * @return DbOperation
     */
    public function groupBy(string $value): self
    {
        $this->groupByStatements[] = $value;

        return $this;
    }

    /**
     * Set group by statements
     *
     * @param array $groups
     * @return $this
     */
    public function setGroupBy(array $groups): self {
        foreach ($groups as $group) {
            $this->groupBy($group);
        }

        return $this;
    }

    /**
     * Set result offset
     *
     * @param int $offset
     * @return DbOperation
     */
    public function offset(int $offset): self
    {
        $this->offsetValue = $offset;

        return $this;
    }

    /**
     * Set result limit
     *
     * @param int $limit
     * @return DbOperation
     */
    public function limit(int $limit): self
    {
        $this->limitValue = $limit;

        return $this;
    }

    /**
     * Execute query
     * @param callable $resultCallback Must return result
     * @return array
     * @throws ExecutionException
     * @throws Exception
     */
    public function execute(Callable $resultCallback = null) {
        if (!$this->query) {
            $this->prepareQuery();
        }

        $stmt = $this->conn->prepare($this->query);
        if (!$stmt || $this->conn->errno) {
            throw new ExecutionException($this->conn);
        }

        if (count($this->bindingParams)) {
            $stmt->bind_param($this->getTypeSequence(), ...$this->bindingParams);
        }

        if (!$stmt->execute()) {
            throw new ExecutionException($stmt);
        }

        $result = $stmt->get_result();

        $returnArray = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[] = !!$resultCallback ? $resultCallback($row) : $row;
            }
        } else {
            $returnArray = [
                'affected_rows' => $stmt->affected_rows,
                'insert_id'     => $stmt->insert_id
            ];
        }

        return $returnArray;
    }

    /**
     * Get SQL query
     *
     * @return string
     */
    public function toSql(): string {
        if (!$this->query) {
            $this->prepareQuery();
        }

        return $this->query;
    }

    /**
     * Return binding parameters
     *
     * @return array
     */
    public function getBindings(): array {
        if (!$this->query) {
            $this->prepareQuery();
        }

        return $this->bindingParams;
    }

    /**
     * Prepare query
     */
    private function prepareQuery()
    {
        $this->query = $this->getBaseQuery();
        $this->applyJoins();
        $this->applyWhereClauses();
        $this->applyGroupBy();
        $this->applyOrderBy();
        $this->applyLimit();
    }

    /**
     * Apply joins
     */
    private function applyJoins() {
        if (!count($this->joins)) {
            return;
        }

        foreach ($this->joins as $join) {
            $this->query .= " join $join[0] on $this->table.$join[1] = $join[0].$join[2]";
        }
    }

    /**
     * Apply preset where clauses
     */
    private function applyWhereClauses() {
        if (!count($this->whereClauses)) {
            return;
        }

        $whereStrings = [];
        foreach ($this->whereClauses as $clause) {
            $placeholder = is_null($clause[2]) ? 'null' : '?';
            $whereStrings[] = "$this->table.$clause[0] $clause[1] $placeholder";
            if (!is_null($clause[2])) {
                $this->bindingParams[] = $clause[2];
            }
        }

        $this->query .= ' where '.implode(' and ', $whereStrings);
    }

    /**
     * Apply preset group by statements
     */
    private function applyGroupBy() {
        if (!count($this->groupByStatements)) {
            return;
        }

        $this->query .= ' group by '.implode(',', $this->groupByStatements);
    }

    /**
     * Apply preset group by statements
     */
    private function applyOrderBy() {
        if (!count($this->orderStatements)) {
            return;
        }

        $this->query .= ' order by '.implode(',', $this->orderStatements);
    }

    /**
     * Apply limit and offset
     */
    private function applyLimit() {
        if (!isset($this->limitValue) || is_null($this->limitValue)) {
            return;
        }

        $this->query .= ' limit '.$this->limitValue;

        if (!isset($this->offsetValue) || is_null($this->offsetValue)) {
            return;
        }

        $this->query .= ' offset '.$this->offsetValue;
    }

    /**
     * Get type sequence for bindings
     *
     * @return string
     * @throws Exception
     */
    private function getTypeSequence() {
        $typeSequence = '';
        foreach ($this->bindingParams as $param) {
            switch (gettype($param)) {
                case 'boolean':
                case 'integer':
                    $typeSequence .= 'i';
                    break;

                case 'double':
                    $typeSequence .= 'd';
                    break;

                case 'string':
                case 'NULL':
                    $typeSequence .= 's';
                    break;

                default:
                    throw new Exception('Error during binding param. Incorrect type - '.gettype($param));
            }
        }

        return $typeSequence;
    }

    /**
     * Returns base query for concrete operation
     *
     * @return string
     */
    protected abstract function getBaseQuery(): string ;
}