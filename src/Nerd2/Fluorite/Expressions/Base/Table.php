<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 15:00
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Table extends BaseExpression
{
    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $alias;

    /**
     * @param string $table
     * @param string $alias
     */
    public function __construct($table, $alias = null)
    {
        switch (substr_count($table, '.')) {
            case 0:
                $this->table = $table;
                break;
            case 1:
                list($this->database, $this->table) = explode('.', $table);
                break;
            default:
                throw new \InvalidArgumentException("Incorrect table reference format - {$table}.");
        }

        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitTable($this);
    }

}