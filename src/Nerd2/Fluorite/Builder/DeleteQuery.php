<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 28.04.16
 * @time 10:19
 */

namespace Nerd2\Fluorite\Builder;


use Nerd2\Fluorite\Expressions\Base\Table;
use Nerd2\Fluorite\Factory\Fluorite;

abstract class DeleteQuery extends BaseQuery
{
    use Traits\WhereTrait;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @param Fluorite $fluorite
     */
    public function __construct(Fluorite $fluorite)
    {
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
}
