<?php

namespace Nerd2\Fluorite\ORM;


use Funky\Option\Option;

class ActiveModel extends EventModel
{
    /**
     * @var EntityManager
     */
    protected static $entityManager = null;

    /**
     * @param EntityManager $entityManager
     */
    public static function setEntityManager(EntityManager $entityManager)
    {
        static::$entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public static function getEntityManager()
    {
        if (is_null(static::$entityManager)) {
            throw new \InvalidArgumentException("Entity manager not specified.");
        }
        return static::$entityManager;
    }

    /**
     * Find item using primary key value.
     *
     * @param $id
     * @return Option
     */
    public static function find($id)
    {
        return static::getEntityManager()->find(static::class, $id);
    }

    /**
     * Find item using attribute value.
     *
     * @param string $attr
     * @param mixed $value
     * @return Option
     */
    public static function findUsingAttr($attr, $value)
    {
        return static::getEntityManager()->findUsingAttr(static::class, $attr, $value);
    }

    /**
     * Find item using callback query builder.
     *
     * @param callable $callback
     * @return Option
     */
    public static function findUsingCallback($callback)
    {
        return static::getEntityManager()->findUsingCallback(static::class, $callback);
    }

    /**
     * @param callable $callback
     * @return ModelCollection
     */
    public static function getListUsingCallback($callback)
    {
        return static::getEntityManager()->getListUsingCallback(static::class, $callback);
    }
}