<?php

namespace Nerd2\Fluorite\Factory;


use Nerd2\Fluorite\Builder\DeleteQuery;
use Nerd2\Fluorite\Builder\InsertQuery;
use Nerd2\Fluorite\Builder\SelectQuery;
use Nerd2\Fluorite\Builder\UpdateQuery;
use Nerd2\Fluorite\DB\Connection;


abstract class Fluorite
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param array $tables
     * @return SelectQuery
     */
    abstract public function selectFrom(...$tables);

    /**
     * @param string $table
     * @return InsertQuery
     */
    abstract public function insertInto($table);

    /**
     * @param string $table
     * @return UpdateQuery
     */
    abstract public function update($table);

    /**
     * @param $table
     * @return DeleteQuery
     */
    abstract public function delete($table);
}