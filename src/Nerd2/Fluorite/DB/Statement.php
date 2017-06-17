<?php

namespace Nerd2\Fluorite\DB;


class Statement extends ResultSet
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

        $this->prepare();
    }

    public function prepare()
    {
        $statement = $this->connection->getPdo()->prepare($this->sql);

        $this->setPdoStatement($statement);
    }

    /**
     * @param int|string $parameter
     * @param mixed $value
     * @param int $dataType
     * @return $this
     */
    public function bind($parameter, $value, $dataType = \PDO::PARAM_STR)
    {
        $this->getPdoStatement()->bindValue($parameter, $value, $dataType);

        return $this;
    }

    /**
     * @param int|string $parameter
     * @param int $value
     * @return $this
     */
    public function bindInt($parameter, $value)
    {
        $this->bind($parameter, $value, \PDO::PARAM_INT);

        return $this;
    }

    /**
     * @param int|string $parameter
     * @param int $value
     * @return $this
     */
    public function bindString($parameter, $value)
    {
        $this->bind($parameter, $value, \PDO::PARAM_STR);

        return $this;
    }

    /**
     * @param int|string $parameter
     * @param bool $value
     * @return $this
     */
    public function bindBool($parameter, $value)
    {
        $this->bind($parameter, $value, \PDO::PARAM_BOOL);

        return $this;
    }

    /**
     * @param array|null $parameters
     * @return $this
     */
    public function execute(array $parameters = null)
    {
        $begin = microtime(true);

        $this->getPdoStatement()->execute($parameters);

        $time = microtime(true) - $begin;

        $rows = $this->getPdoStatement()->rowCount();

        if (config("app.debug")) {
            error_log(sprintf(
                "Time: %0.2f, Rows: %d, Sql: %s, Data: %s",
                $time,
                $rows,
                $this->sql,
                json_encode($parameters, JSON_UNESCAPED_UNICODE)
            ));
        }

        $this->connection->queries ++;

        return $this;
    }

}