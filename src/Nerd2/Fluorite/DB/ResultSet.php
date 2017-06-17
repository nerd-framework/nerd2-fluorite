<?php

namespace Nerd2\Fluorite\DB;


use Funky\Option\Option;
use Traversable;

abstract class ResultSet implements \Countable, \IteratorAggregate
{
    /**
     * @var \PDOStatement
     */
    private $statement;

    /**
     * Return PDOStatement encapsulated into ResultSet.
     *
     * @return \PDOStatement
     */
    public function getPdoStatement()
    {
        return $this->statement;
    }

    /**
     * Encapsulate PDOStatement into ResultSet.
     *
     * @param \PDOStatement $statement
     */
    public function setPdoStatement(\PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Returns a single column from the next row of a result set.
     *
     * @param int $column [optional]
     * @return string Returns a single column in the next row of a result set.
     */
    public function fetchOneColumn($column = 0)
    {
        return $this->statement->fetchColumn($column);
    }

    /**
     * Fetches the next row from a result set.
     *
     * @return mixed The return value of this function on success depends on the fetch type. In
     * all cases, FALSE is returned on failure.
     */
    public function fetchOneRow()
    {
        return $this->statement->fetch();
    }

    /**
     * Returns a single column from the next row of a result set encapsulated into Option.
     *
     * @param int $column
     * @return Option
     */
    public function takeOneColumn($column = 0)
    {
        return Option::wrap($this->fetchOneColumn($column), false);
    }

    /**
     * Fetches the next row from a result set encapsulated into Option.
     *
     * @return Option
     */
    public function takeOneRow()
    {
        return Option::wrap($this->fetchOneRow(), false);
    }

    /**
     * @param null $column
     * @return array
     */
    public function fetchAll($column = null)
    {
        $result = $this->statement->fetchAll();

        if (!is_null($column)) {
            return array_map(function ($row) use (&$column) {
                return $row[$column];
            }, $result);
        }

        return $result;
    }

    /**
     * @return \Generator
     */
    public function fetchGenerator()
    {
        while ($row = $this->fetchOneRow()) {
            yield $row;
        }
    }

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @return int
     */
    public function getRowCount()
    {
        return $this->statement->rowCount();
    }

    /**
     * @see getRowCount()
     *
     * @return int
     */
    public function count()
    {
        return $this->getRowCount();
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \IteratorIterator($this->statement);
    }
}