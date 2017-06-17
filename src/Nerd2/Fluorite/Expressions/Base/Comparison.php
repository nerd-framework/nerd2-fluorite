<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 15:56
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\Brackets;
use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Comparison extends BaseExpression
{
    const EQ   = '=';
    const NSEQ = '<=>';
    const NEQ  = '<>';
    const LT   = '<';
    const LTE  = '<=';
    const GT   = '>';
    const GTE  = '>=';
    const IN   = 'IN';
    const LIKE = 'LIKE';
    const BETWEEN = 'BETWEEN';
    const IS   = 'IS';
    const IS_NOT = 'IS NOT';
    const ON   = 'ON';
    const USING = 'USING';

    /**
     * @var BaseExpression
     */
    private $left;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var BaseExpression
     */
    private $right;

    /**
     * Comparison constructor.
     * @param Expression $left
     * @param string $operator
     * @param Expression $right
     */
    public function __construct(Expression $left, $operator, Expression $right)
    {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    /**
     * @return BaseExpression
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return BaseExpression
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param Expression $that
     * @return CompositeExpression
     */
    public function compositeOr(Expression $that)
    {
        $left  = ($this instanceof Comparison) ? $this : new Brackets($this);
        $right = ($this instanceof Comparison) ? $that : new Brackets($that);

        return new CompositeExpression(CompositeExpression::CONJ_OR, $left, $right);
    }

    /**
     * @param Expression $that
     * @return CompositeExpression
     */
    public function compositeAnd(Expression $that)
    {
        $left  = ($this instanceof Comparison) ? $this : new Brackets($this);
        $right = ($this instanceof Comparison) ? $that : new Brackets($that);

        return new CompositeExpression(CompositeExpression::CONJ_AND, $left, $right);
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitComparison($this);
    }

}