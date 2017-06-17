<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 23.03.2016
 * Time: 12:26
 */

namespace Nerd2\Fluorite\ORM;


class Nothing extends Model
{
    private static $instance;

    private function __construct() { }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function __get($name)
    {
        return $this;
    }

    public function __isset($name)
    {
        return false;
    }

    public function __set($name, $value)
    {
        throw new \InvalidArgumentException("Nothing could not be changed.");
    }

    public function __call($name, $arguments)
    {
        return $this;
    }

    public static function __callStatic($name, $arguments)
    {
        return static::getInstance();
    }

    public static function hydrate($data)
    {
        return static::getInstance();
    }

    public function jsonSerialize()
    {
        return null;
    }

    public function __toString()
    {
        return 'NULL';
    }
}