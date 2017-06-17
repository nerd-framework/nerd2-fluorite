<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.16
 * Time: 17:55
 */

namespace Nerd2\Fluorite\ORM;


use Funky\Option\Option;
use Kote\Fluorite\Factory\Fluorite;
use Nerd2\Fluorite\ORM\Collections\ModelCollection;

class EntityManager
{
    /**
     * @var Fluorite
     */
    private $fluorite;

    /**
     * EntityManager constructor.
     * @param Fluorite $fluorite
     */
    public function __construct(Fluorite $fluorite)
    {
        $this->fluorite = $fluorite;
    }

    /**
     * @param Model $class
     * @param mixed $primaryKey
     * @return Option
     * @throws Exceptions\ModelException
     */
    public function find($class, $primaryKey)
    {
        $query = $this->fluorite->selectFrom($class::getTableName())
            ->filter($class::getPrimaryKey(), $primaryKey);

        $object = $query->first();

        return wrap($object, false)->map([$class, "hydrate"]);
    }

    /**
     * Find entity using attribute value.
     *
     * @param Model $class
     * @param $attr
     * @param $value
     * @return Option
     */
    public function findUsingAttr($class, $attr, $value)
    {
        $query = $this->fluorite->selectFrom($class::getTableName())
            ->filter($attr, $value);

        $object = $query->first();

        return wrap($object, false)->map([$class, "hydrate"]);
    }

    /**
     * Find entity using callback query builder.
     *
     * @param Model $class
     * @param callable $callback(\SelectQuery)
     * @return Option
     */
    public function findUsingCallback($class, $callback)
    {
        $callback($query = $this->fluorite->selectFrom($class::getTableName()));

        $object = $query->first();

        return wrap($object, false)->map([$class, "hydrate"]);
    }

    /**
     * Get list of entities using attribute value.
     *
     * @param Model $class
     * @param $attr
     * @param $value
     * @return ModelCollection
     */
    public function getListUsingAttr($class, $attr, $value)
    {
        $query = $this->fluorite->selectFrom($class::getTableName())->filter($attr, $value);

        return (new ModelCollection($query->all()))->map([$class, "hydrate"]);
    }

    /**
     * Get list of entities using callback query builder.
     *
     * @param $class
     * @param $callback
     * @return ModelCollection
     */
    public function getListUsingCallback($class, $callback)
    {
        $callback($query = $this->fluorite->selectFrom($class::getTableName()));

        return (new ModelCollection($query->all()))->map([$class, "hydrate"]);
    }
}