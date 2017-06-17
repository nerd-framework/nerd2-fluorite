<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.2016
 * Time: 9:56
 */

namespace Nerd2\Fluorite\ORM;


abstract class Model extends ModelWithRelations implements \ArrayAccess, \JsonSerializable
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $originalData;

    /**
     * @var array
     */
    protected static $disabledProperties = [];

    /**
     * Returns array of changed attributes.
     *
     * @return array
     */
    public function getChangedAttributes()
    {
        return array_diff($this->data, $this->originalData);
    }

    public function syncChangedAttributes()
    {
        $this->originalData = $this->data;

        return $this;
    }

    /**
     * @return mixed
     * @throws Exceptions\ModelException
     */
    public function id()
    {
        return $this->data[static::getPrimaryKey()];
    }

    /**
     * @param $data
     * @return static
     */
    public static function hydrate($data)
    {
        $object = new static;
        $object->data = $object->originalData = $data;

        return $object;
    }

    /**
     * @return mixed
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, static::$disabledProperties)) {
            return $this->getRelation($name);
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $this->getRelation($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exceptions\ModelException
     */
    public function __set($name, $value)
    {
        if ($name == static::getPrimaryKey()) {
            throw new \InvalidArgumentException("Primary key could not be set manually.");
        }

        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        throw new \BadMethodCallException("You can not unset any model fields dynamically.");
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @param string $offset
     * @return Model
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    function jsonSerialize()
    {
        return $this->data;
    }
}