<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 10:58
 */

namespace Nerd2\Fluorite\Expressions;


class Brackets extends ExpressionContainer
{
    /**
     * Brackets constructor.
     * @param $expression
     */
    public function __construct(Expression $expression)
    {
        $this->setExpression($expression);
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitBrackets($this);
    }

 
}