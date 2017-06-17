<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 12:54
 */

namespace Nerd2\Fluorite\Expressions;


use Nerd2\Fluorite\Expressions\Base\BaseExpression;

abstract class ExpressionListContainer extends BaseExpression implements \Countable, \IteratorAggregate
{
    /**
     * @var Expression[]
     */
    private $expressions = [];

    /**
     * @return int
     */
    public function count()
    {
        return count($this->expressions);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->expressions);
    }

    /**
     * @param Expression[] $expressions
     * @return $this
     */
    public function addExpression(Expression ...$expressions)
    {
        $this->expressions = array_merge($this->expressions, $expressions);

        return $this;
    }

    /**
     * @param Expression[] $expressions
     * @return $this
     */
    public function setExpressions(array $expressions)
    {
        $this->expressions = $expressions;

        return $this;
    }

    /**
     * @return Expression[]
     */
    public function getExpressionList()
    {
        return $this->expressions;
    }

    public function clear()
    {
        while (array_shift($this->expressions));
    }
}