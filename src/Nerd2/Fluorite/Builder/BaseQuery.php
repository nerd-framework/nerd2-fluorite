<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 19:52
 */

namespace Nerd2\Fluorite\Builder;


use Nerd2\Fluorite\DB\ResultSet;
use Nerd2\Fluorite\Factory\Fluorite;
use Nerd2\Fluorite\QueryCloak;

abstract class BaseQuery
{
    /**
     * @var Fluorite
     */
    private $fluorite;

    /**
     * @param Fluorite $fluorite
     */
    public function __construct(Fluorite $fluorite)
    {
        $this->fluorite = $fluorite;
    }

    /**
     * @return QueryCloak
     */
    abstract protected function buildQuery();

    /**
     * @return ResultSet
     */
    public function execute()
    {
        $cloak = $this->buildQuery();

        if (config('app.debug')) {
            error_log((string) $cloak);
        }

        return $this->fluorite
            ->getConnection()
            ->prepare($cloak->getSql())
            ->execute($cloak->getAttributes());
    }

    /**
     * @return string
     */
    public function debug()
    {
        return (string) $this->buildQuery();
    }

    /**
     * @param null $column
     * @return array
     */
    public function all($column = null)
    {
        $cloak = $this->buildQuery();

        $statement = $this->fluorite->getConnection()->prepare($cloak->getSql());
        $statement->execute($cloak->getAttributes());

        return $statement->fetchAll($column);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        if ($this instanceof SelectQuery) {
            $this->limit(1);
        }

        $cloak = $this->buildQuery();

        $connection = $this->fluorite->getConnection();

        $statement = $connection->prepare($cloak->getSql());
        $statement->execute($cloak->getAttributes());

        return $statement->fetchOneRow();
    }

    /**
     * @param int $column
     * @return string
     */
    public function column($column = 0)
    {
        if ($this instanceof SelectQuery) {
            $this->limit(1);
        }

        $cloak = $this->buildQuery();

        $statement = $this->fluorite->getConnection()->prepare($cloak->getSql());
        $statement->execute($cloak->getAttributes());

        return $statement->fetchOneColumn($column);
    }

    /**
     * @return \Generator
     */
    public function generate()
    {
        $cloak = $this->buildQuery();

        $statement = $this->fluorite->getConnection()->prepare($cloak->getSql());
        $statement->execute($cloak->getAttributes());

        foreach ($statement as $row) {
            yield $row;
        }
    }

}