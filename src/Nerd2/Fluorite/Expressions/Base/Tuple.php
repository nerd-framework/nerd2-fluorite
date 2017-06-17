<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 28.03.2016
 * @time 13:47
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\ExpressionListContainer;
use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Tuple extends ExpressionListContainer
{
    const GLUE_SPACE = ' ';
    const GLUE_COMMA = ',';

    /**
     * @var string
     */
    private $glue;

    /**
     * @param string $glue
     * @param Expression[] ...$expressions
     */
    public function __construct($glue = self::GLUE_COMMA, Expression ...$expressions)
    {
        $this->glue = $glue;
        $this->setExpressions($expressions);
    }

    /**
     * @return string
     */
    public function getGlue()
    {
        return $this->glue;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitTuple($this);
    }

}