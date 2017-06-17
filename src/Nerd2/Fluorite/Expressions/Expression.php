<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 15:56
 */

namespace Nerd2\Fluorite\Expressions;


interface Expression
{
    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor);
}