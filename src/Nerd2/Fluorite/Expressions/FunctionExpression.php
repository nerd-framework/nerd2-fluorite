<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 13:35
 */

namespace Nerd2\Fluorite\Expressions;


use Nerd2\Fluorite\Expressions\Base\Tuple;

class FunctionExpression extends ExpressionContainer
{
    const ARGUMENT_DELIMITER = ',';

    private $function;

    public function __construct($function, Expression ...$expression)
    {
        $this->function = $function;
        $this->setExpression(new Tuple(self::ARGUMENT_DELIMITER, ...$expression));
    }

    /**
     * @return mixed
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitFunctionExpression($this);
    }

}