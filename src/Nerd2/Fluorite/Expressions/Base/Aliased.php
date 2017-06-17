<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 09.06.16
 * @time 17:53
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Aliased extends BaseExpression
{
    private $expression;

    private $alias;

    /**
     * Aliased constructor.
     * @param $expression
     * @param $alias
     */
    public function __construct(BaseExpression $expression, $alias)
    {
        $this->expression = $expression;
        $this->alias = $alias;
    }

    /**
     * @return BaseExpression
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitAliasedExpression($this);
    }
}