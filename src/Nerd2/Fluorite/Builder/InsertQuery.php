<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 29.03.2016
 * @time 10:01
 */

namespace Nerd2\Fluorite\Builder;


use Nerd2\Fluorite\Expressions\Base\Column;
use Nerd2\Fluorite\Expressions\Base\Table;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\Expressions\Base\Value;
use Nerd2\Fluorite\Factory\Fluorite;

abstract class InsertQuery extends BaseQuery
{
    /**
     * @var Table
     */
    private $table;

    /**
     * @var Tuple
     */
    private $columns;

    /**
     * @var Tuple
     */
    private $values;

    /**
     * @param Fluorite $fluorite
     */
    public function __construct(Fluorite $fluorite)
    {
        parent::__construct($fluorite);

        $this->columns = new Tuple(Tuple::GLUE_COMMA);
        $this->values  = new Tuple(Tuple::GLUE_COMMA);
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return Tuple
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return Tuple
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function into($table)
    {
        $this->table = Table::autobox($table);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function set($column, $value)
    {
        $this->getColumns()->addExpression(Column::autobox($column));
        $this->getValues()->addExpression(Value::autobox($value));

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function fill(array $values)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }
}