<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 29.03.2016
 * @time 11:53
 */

namespace Nerd2\Fluorite\Factory;


use Nerd2\Fluorite\Builder\DeleteQuery;
use Nerd2\Fluorite\Builder\InsertQuery;
use Nerd2\Fluorite\Builder\SelectQuery;
use Nerd2\Fluorite\Builder\UpdateQuery;
use Nerd2\Fluorite\Concrete\MySql\MySqlDeleteQuery;
use Nerd2\Fluorite\Concrete\MySql\MySqlInsertQuery;
use Nerd2\Fluorite\Concrete\MySql\MySqlSelectQuery;
use Nerd2\Fluorite\Concrete\MySql\MySqlUpdateQuery;

class MySqlFluorite extends Fluorite
{
    /**
     * @param array $tables
     * @return SelectQuery
     */
    public function selectFrom(...$tables)
    {
        return (new MySqlSelectQuery($this))->from(...$tables);
    }

    /**
     * @param string $table
     * @return InsertQuery
     */
    public function insertInto($table)
    {
        return (new MySqlInsertQuery($this))->into($table);
    }

    /**
     * @param $table
     * @return UpdateQuery
     */
    public function update($table)
    {
        return (new MySqlUpdateQuery($this))->table($table);
    }

    /**
     * @param $table
     * @return DeleteQuery
     */
    public function delete($table)
    {
        return (new MySqlDeleteQuery($this))->table($table);
    }
}