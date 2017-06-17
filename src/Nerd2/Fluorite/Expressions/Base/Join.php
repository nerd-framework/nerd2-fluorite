<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 29.03.2016
 * @time 15:41
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\ExpressionContainer;
use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Join extends ExpressionContainer
{
    const INNER_JOIN = 'INNER';
    const LEFT_JOIN = 'LEFT';
    const RIGHT_JOIN = 'RIGHT';

    private $joinType;

    public function __construct($joinType = self::INNER_JOIN, Expression ...$expressions)
    {
        $this->joinType = $joinType;
        $this->setExpression(...$expressions);
    }

    /**
     * @return string
     */
    public function getJoinType()
    {
        return $this->joinType;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->acceptJoin($this);
    }
}