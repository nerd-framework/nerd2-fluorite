<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 19:44
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Couple extends BaseExpression
{
    /**
     * @var Expression
     */
    private $first;

    /**
     * @var Expression
     */
    private $second;

    /**
     * @param Expression|null $first
     * @param Expression|null $second
     */
    public function __construct(Expression $first = null, Expression $second = null)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @param Expression $first
     * @return Couple
     */
    public function setFirst(Expression $first)
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @param Expression $second
     * @return Couple
     */
    public function setSecond(Expression $second)
    {
        $this->second = $second;
        return $this;
    }


    /**
     * @return BaseExpression
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return BaseExpression
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitCouple($this);
    }

}