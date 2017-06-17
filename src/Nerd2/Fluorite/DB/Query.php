<?php

namespace Nerd2\Fluorite\DB;


class Query extends ResultSet
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $sql;

    /**
     * @param string $sql
     * @param Connection $connection
     */
    public function __construct($sql, Connection $connection)
    {
        $this->connection = $connection;
        $this->sql = $sql;

        $this->query();
    }

    private function query()
    {
        $query = $this->connection->getPdo()->query($this->sql);
        $this->connection->queries ++;
        $this->setPdoStatement($query);
    }
}