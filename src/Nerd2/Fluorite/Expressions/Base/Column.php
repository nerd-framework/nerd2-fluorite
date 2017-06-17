<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 14:53
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class Column extends BaseExpression
{
    private $database;

    private $table;

    private $column;

    private $alias;

    public function __construct($column, $alias = null)
    {
        switch (substr_count($column, '.')) {
            case 0:
                $this->column = $column;
                break;
            case 1:
                list ($table, $column) = explode('.', $column);
                $this->table = $table;
                $this->column = $column;
                break;
            case 2:
                list ($database, $table, $column) = explode('.', $column);
                $this->database = $database;
                $this->table = $table;
                $this->column = $column;
                break;
            default:
                throw new \InvalidArgumentException("Incorrect column reference format - {$column}.");
        }

        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return null
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
        $visitor->visitColumn($this);
    }

    /**
     * @param $value
     * @param null $alias
     * @return static
     */
    public static function autobox($value, $alias = null)
    {
        if ($value instanceof Expression && isset($alias)) {
            return new Aliased($value, $alias);
        }

        if ($value instanceof Expression) {
            return $value;
        }
        
        if (is_string($value) && preg_match('~([\w.]+)\s+AS\s+([\w.]+)~', $value, $matches)) {
            list (, $value, $alias) = $matches;
        }

        return new static($value, $alias);
    }
}