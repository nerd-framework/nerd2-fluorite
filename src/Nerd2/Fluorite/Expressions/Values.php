<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 12:24
 */

namespace Nerd2\Fluorite\Expressions;


use Nerd2\Fluorite\Expressions\Base\Value;

class Values extends ExpressionListContainer
{
    /**
     * @param Expression[] ...$values
     */
    public function __construct(Expression ...$values)
    {
        $this->setExpressions($values);
    }

    /**
     * @param array $range
     * @return static
     */
    public static function fromArray(array $range)
    {
        $instance = new static();

        foreach ($range as $item) {
            $instance->addExpression(Value::autobox($item));
        }

        return $instance;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitValues($this);
    }

}