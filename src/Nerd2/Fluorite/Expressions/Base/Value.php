<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.16
 * @time 18:43
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Value extends BaseExpression
{
    private $value;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitValue($this);
    }


}