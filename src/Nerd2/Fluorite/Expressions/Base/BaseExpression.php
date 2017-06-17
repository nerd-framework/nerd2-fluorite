<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 13:10
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\Expression;

abstract class BaseExpression implements Expression
{
    use Traits\ComparisonTrait;

    /**
     * @param $value
     * @return static
     */
    public static function autobox($value)
    {
        return $value instanceof Expression ? $value : new static($value);
    }


}