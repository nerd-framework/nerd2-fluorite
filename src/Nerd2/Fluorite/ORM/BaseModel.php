<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.16
 * Time: 17:26
 */

namespace Nerd2\Fluorite\ORM;


abstract class BaseModel
{
    /**
     * @var string
     */
    protected static $primaryKey = "id";

    /**
     * @var string
     */
    protected static $tableName;

    /**
     * Returns primary key assigned to this Model.
     *
     * @return string
     * @throws Exceptions\ModelException
     */
    public static function getPrimaryKey()
    {
        if (is_null(static::$primaryKey)) {
            throw new Exceptions\ModelException(sprintf("Primary key is not specified in %s.", static::class));
        }
        return static::$primaryKey;
    }

    /**
     * Returns table name assigned to this Model.
     *
     * @return string
     * @throws Exceptions\ModelException
     */
    public static function getTableName()
    {
        if (is_null(static::$tableName)) {
            throw new Exceptions\ModelException(sprintf("Table name is not specified in %s.", static::class));
        }
        return static::$tableName;
    }

    /**
     * Returns table.pk assigned to this Model.
     *
     * @return string
     * @throws Exceptions\ModelException
     */
    public static function getFullPrimaryKey()
    {
        return static::getTableName() . "." . static::getPrimaryKey();
    }
}