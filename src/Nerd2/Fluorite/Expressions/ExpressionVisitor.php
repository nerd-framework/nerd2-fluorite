<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 15:56
 */

namespace Nerd2\Fluorite\Expressions;


use Nerd2\Fluorite\Expressions\Base\Aliased;
use Nerd2\Fluorite\Expressions\Base\Comparison;
use Nerd2\Fluorite\Expressions\Base\CompositeExpression;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\Visitor;

abstract class ExpressionVisitor extends Visitor
{
    abstract public function visitColumn(Base\Column $column);

    abstract public function visitTable(Base\Table $table);

    abstract public function visitValue(Base\Value $value);

    abstract public function visitConstant(Base\Constant $constant);

    abstract public function visitCouple(Base\Couple $couple);

    abstract public function visitComparison(Comparison $comparison);

    abstract public function visitCompositeExpression(CompositeExpression $compositeExpression);

    abstract public function visitCriteria(Criteria $criteria);

    abstract public function visitOrder(Order $order);

    abstract public function visitBrackets(Brackets $brackets);

    abstract public function visitValues(Values $values);

    abstract public function visitFunctionExpression(FunctionExpression $functionExp);

    abstract public function visitTuple(Tuple $tuple);

    abstract public function visitAliasedExpression(Aliased $aliased);

    abstract public function acceptJoin(Base\Join $join);

    abstract public function acceptRaw(Base\Raw $raw);
}