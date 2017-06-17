<?php

namespace Nerd2\Fluorite\DB;


use Nerd2\Fluorite\QueryCloak;

class Connection
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var \PDO
     */
    private $pdo;

    public $queries = 0;

    public function __construct(Configuration $config)
    {
        $this->setConfigurationInterface($config);
        $this->connect();
    }

    private function setConfigurationInterface(Configuration $config)
    {
        $this->config = $config;
    }

    private function connect()
    {
        $this->pdo = new \PDO(
            $this->config->getDsn(),
            $this->config->getUsername(),
            $this->config->getPassword(),
            $this->config->getOptions()
        );
    }

    public function disconnect()
    {
        $this->pdo = null;
    }

    public function reconnect()
    {
        $this->disconnect();
        $this->connect();
    }

    /**
     * @param \Closure $callback
     * @return mixed
     * @throws \Exception
     */
    public function inTransaction(\Closure $callback)
    {
        $this->getPdo()->beginTransaction();

        try {
            $result = $callback->call($this);
        } catch (\Exception $exception) {
            $this->getPdo()->rollBack();
            throw $exception;
        }

        $this->getPdo()->commit();

        return $result;
    }

    /**
     * @param string $sql
     * @return Statement
     */
    public function prepare($sql)
    {
        return new Statement($sql, $this);
    }

    /**
     * @param string $sql
     * @return ResultSet
     */
    public function query($sql)
    {
        return new Query($sql, $this);
    }

    /**
     * @param QueryCloak $cloak
     * @return ResultSet
     */
    public function execute(QueryCloak $cloak)
    {
        $sql = $cloak->getSql();

        $statement = new Statement($sql, $this);

        $result = $statement->execute($cloak->getAttributes());

        return $result;
    }
    
    /**
     * @param string|null $name
     * @return string
     */
    public function getLastInsertId($name = null)
    {
        return $this->getPdo()->lastInsertId($name);
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param $string
     * @param int $parameterType
     * @return string
     */
    public function quote($string, $parameterType = \PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $parameterType);
    }

    public function __destruct()
    {
        if (config("app.debug")) {
            error_log("Queries: " . $this->queries);
        }
    }
}