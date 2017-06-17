<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 31.03.16
 * @time 19:48
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Raw extends BaseExpression
{
    private $expression;

    private $parameters;

    /**
     * RawExpression constructor.
     * @param $expression
     * @param $parameters
     */
    public function __construct($expression, array $parameters)
    {
        $this->expression = $expression;
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->acceptRaw($this);
    }
}