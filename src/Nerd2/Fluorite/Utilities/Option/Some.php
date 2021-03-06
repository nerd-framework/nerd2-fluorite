<?php

namespace Nerd2\Fluorite\Utilities\option;

/**
 * Class Some
 * @package Tools\Optional
 */
final class Some extends Option {

    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function isEmpty() {
        return false;
    }

    public function get() {
        return $this->value;
    }

    public function __toString() {
        return "Some(" . $this->value . ")";
    }

    public function getIterator() {
        yield $this->get();
    }

    public function nonEmpty() {
        return true;
    }

    public function getOrElse($other) {
        return $this->get();
    }

    public function orFalse() {
        return $this->get();
    }

    public function orZero() {
        return $this->get();
    }

    public function orNull() {
        return $this->get();
    }

    public function orEmpty() {
        return $this->get();
    }

    public function orCall($callable) {
        return $this->get();
    }

    public function orElse(Option $alternative) {
        return $this;
    }

    public function getOrThrow($exception, ...$args) {
        return $this->get();
    }

    /**
     * @param $callable
     * @param $args
     * @return Option
     */
    public function map($callable, ...$args) {
        return new Some($callable($this->get(), ...$args));
    }

    public function flatMap($callable) {
        $result = $callable($this->get());
        if (!$result instanceof Option) {
            throw new OptionException("Callable passed to .flatMap() must return Option object!");
        }
        return $result;
    }

    public function filter($predicate) {
        if (is_callable($predicate)) {
            return $predicate($this->get()) ? $this : None::instance();
        } else {
            return $this->get() === $predicate ? $this : None::instance();
        }
    }

    public function reject($predicate) {
        if (is_callable($predicate)) {
            return $predicate($this->get()) ? None::instance() : $this;
        } else {
            return $this->get() === $predicate ? None::instance() : $this;
        }
    }

    /**
     * @return $this
     */
    public function toInt() {
        if (is_numeric($this->get())) {
            return Option::Some(intval($this->get()));
        } else {
            return None::instance();
        }
    }


    public function then($callable, $otherwise = null) {
        $callable($this->get());
        return $this;
    }


    /**
     * @param \Closure $producer
     * @return Option
     */
    public function otherwise(\Closure $producer) {
        return $this;
    }


    public function select($value) {
        return $this->get() === $value ? $this : None::instance();
    }

    public function selectInstance($object) {
        return ($this->get() instanceof $object) ? $this : None::instance();
    }

    /**
     * @param $key
     * @return $this
     */
    public function sel($key) {
        return (is_array($this->get()) && array_key_exists($key, $this->get()))
            ? Option::Some($this->get()[$key]) : None::instance();
    }

    /**
     * @param $method
     * @param $args
     * @return $this
     */
    public function call($method, ...$args) {
        return (is_object($this->get()) && method_exists($this->get(), $method))
            ? Option::Some($this->get()->$method(...$args)) : None::instance();
    }

    /**
     * @param $callable
     * @param ...$args
     * @return $this
     */
    public function orThrow($callable, ...$args) {
        return $this;
    }

    public function offsetExists($offset) {
        return isset($this->get()[$offset]);
    }

    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? Option::Some($this->get()[$offset]) : Option::None();
    }

}
