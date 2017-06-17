<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 12:48
 */

namespace Nerd2\Fluorite\Expressions;


use Nerd2\Fluorite\Expressions\Base\BaseExpression;

abstract class ExpressionContainer extends BaseExpression
{
    /**
     * @var Expression
     */
    private $expression;

    /**
     * @param Expression $expression
     */
    public function setExpression(Expression $expression = null)
    {
        $this->expression = $expression;
    }

    /**
     * @return Expression
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return bool
     */
    public function hasExpression()
    {
        return ! is_null($this->expression);
    }
}