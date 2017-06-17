<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 10:47
 */

namespace Nerd2\Fluorite\Expressions;


use Nerd2\Fluorite\Expressions\Base\Column;

class Order extends ExpressionContainer
{
    const DIR_ASC  = 'ASC';
    const DIR_DESC = 'DESC';

    /**
     * @var string
     */
    private $direction;

    /**
     * @param Expression $expression
     * @param string $direction
     */
    public function __construct(Expression $expression, $direction = self::DIR_ASC)
    {
        $this->setExpression($expression);
        $this->setDirection($direction);
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitOrder($this);
    }

    /**
     * @param $value
     * @param string $direction
     * @return static
     */
    public static function autobox($value, $direction = self::DIR_ASC)
    {
        if ($value instanceof Order) {
            return $value;
        }

        if (is_string($value) && is_null($direction) && strpos($value, ' ') !== false) {
            list ($value, $direction) = explode(' ', $value);
        }

        return $value instanceof Expression
            ? new static($value, $direction)
            : new static(Column::autobox($value), $direction);
    }

}