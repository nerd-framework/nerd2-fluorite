<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 15:56
 */

namespace Nerd2\Fluorite\Expressions;


use Nerd2\Fluorite\Expressions\Base\CompositeExpression;

class Criteria extends ExpressionContainer
{
    /**
     * @param Expression|null $expression
     */
    public function __construct(Expression $expression = null)
    {
        $this->setExpression($expression);
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function where(Expression $expression)
    {
        if (! $this->hasExpression()) {
            $this->setExpression($expression);
        } else {
            $this->setExpression(new CompositeExpression(CompositeExpression::CONJ_AND,
                $this->wrapIfNeeded($this->getExpression()),
                $this->wrapIfNeeded($expression)
            ));
        }

        return $this;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function orWhere(Expression $expression)
    {
        if (! $this->hasExpression()) {
            $this->setExpression($expression);
        } else {
            $this->setExpression(new CompositeExpression(CompositeExpression::CONJ_OR,
                $this->wrapIfNeeded($this->getExpression()),
                $this->wrapIfNeeded($expression)
            ));
        }

        return $this;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitCriteria($this);
    }

    /**
     * @param Expression $e
     * @return Expression
     */
    private function wrapIfNeeded(Expression $e)
    {
        return $e instanceof CompositeExpression ? new Brackets($e) : $e;
    }


}