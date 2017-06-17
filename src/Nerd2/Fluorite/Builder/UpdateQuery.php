<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.04.16
 * @time 14:30
 */

namespace Nerd2\Fluorite\Builder;


use Nerd2\Fluorite\Expressions\Base\Column;
use Nerd2\Fluorite\Expressions\Base\Table;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\Factory\Fluorite;

abstract class UpdateQuery extends BaseQuery
{
    use Traits\WhereTrait;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var Tuple
     */
    private $updates;

    /**
     * @param Fluorite $fluorite
     */
    public function __construct(Fluorite $fluorite)
    {
        $this->updates = new Tuple(Tuple::GLUE_COMMA);
        parent::__construct($fluorite);

        $this->initializeWhereCriteria();
    }

    /**
     * @param $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = Table::autobox($table);

        return $this;
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
    public function getUpdates()
    {
        return $this->updates;
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function set($column, $value)
    {
        $this->getUpdates()->addExpression(
            Column::autobox($column)->eq($value)
        );

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