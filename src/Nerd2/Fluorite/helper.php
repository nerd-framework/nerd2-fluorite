<?php

namespace Nerd2\Fluorite;

use Nerd2\Fluorite\Expressions\Base\BaseExpression;
use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\Values;

/**
 * @param string $column
 * @param null $alias
 * @return BaseExpression
 */
function column($column, $alias = null)
{
    return Expressions\Base\Column::autobox($column, $alias);
}

/**
 * @param $value
 * @return Expressions\Base\BaseExpression
 */
function value($value)
{
    return Expressions\Base\Value::autobox($value);
}

/**
 * @param array $values
 * @return Expressions\Base\BaseExpression
 */
function values(...$values)
{
    if (count($values) == 1 && is_array($values[0])) {
        $values = $values[0];
    }

    return Expressions\Values::fromArray($values);
}

/**
 * @param $table
 * @return Expressions\Base\BaseExpression
 */
function table($table)
{
    return Expressions\Base\Table::autobox($table);
}

/**
 * @param $column
 * @param $direction
 * @return BaseExpression
 */
function order($column, $direction = 'ASC')
{
    return Expressions\Order::autobox($column, $direction);
}

/**
 * @param $expression
 * @param $parameters
 * @return Expression
 */
function raw($expression, $parameters = []): Expression
{
    return new Expressions\Base\Raw($expression, $parameters);
}

/**
 * @param $name
 * @param array $args
 * @return Expressions\FunctionExpression
 */
function func($name, ...$args)
{
    return new Expressions\FunctionExpression($name, ...Values::fromArray($args));
}

