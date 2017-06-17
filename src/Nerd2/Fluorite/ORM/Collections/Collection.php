<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 05.05.16
 * @time 07:43
 */

namespace Nerd2\Fluorite\ORM\Collections;

class Collection implements \Countable, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param array $initial
     */
    public function __construct(array $initial = [])
    {
        $this->items = $initial;
    }

    public function count()
    {
        return count($this->items);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function map($callback)
    {
        return new static(array_map($callback, $this->items));
    }

    public function accept($callback)
    {
        return new static(array_values(array_filter($this->items, $callback)));
    }

    public function reject($callback)
    {
        return $this->accept(function ($item) use ($callback) { return ! $callback($item); });
    }

    public function unique()
    {
        return new static(array_unique($this->items));
    }

    public function first()
    {
        if (count($this->items)) {
            return $this[0];
        }
        return null;
    }

    public function asArray()
    {
        return $this->items;
    }
}