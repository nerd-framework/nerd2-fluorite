<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 28.03.2016
 * @time 11:49
 */

namespace Nerd2\Fluorite\Builder\Traits;


use Nerd2\Fluorite\Expressions\Base\Column;
use Nerd2\Fluorite\Expressions\Criteria;
use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\Values;

trait WhereTrait
{
    /**
     * @var Criteria
     */
    private $where;

    public function initializeWhereCriteria()
    {
        $this->where = new Criteria();
    }

    /**
     * @return Criteria
     */
    public function getWhereCriteria()
    {
        return $this->where;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function where(Expression $expression)
    {
        $this->where->where($expression);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return $this
     */
    public function filter($column, $value)
    {
        if (is_array($value)) {
            $this->where(Column::autobox($column)->in(Values::fromArray($value)));
        } else {
            $this->where(Column::autobox($column)->eq($value));
        }

        return $this;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function orWhere(Expression $expression)
    {
        $this->where->orWhere($expression);

        return $this;
    }

    /**
     * @param array $columns
     * @param string $keywords
     * @return $this
     */
    abstract public function fulltext(array $columns, $keywords);
}