<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 10:53
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Constant extends Value
{
    /**
     * @var Constant[]
     */
    private static $constants = [];

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitConstant($this);
    }

    /**
     * @param $value
     * @return Constant
     */
    public static function fromString($value)
    {
        if (!isset(self::$constants[$value])) {
            self::$constants[$value] = new static($value);
        }

        return self::$constants[$value];
    }

}