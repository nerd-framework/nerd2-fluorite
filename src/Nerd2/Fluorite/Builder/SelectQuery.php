<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 19:53
 */

namespace Nerd2\Fluorite\Builder;


use Nerd2\Fluorite\Expressions\Base\Column;
use Nerd2\Fluorite\Expressions\Base\Join;
use Nerd2\Fluorite\Expressions\Base\Table;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\Order;
use Nerd2\Fluorite\Factory\Fluorite;
use function Nerd2\Fluorite\column as col;
use function Nerd2\Fluorite\func as fnc;
use function Nerd2\Fluorite\raw;


abstract class SelectQuery extends BaseQuery implements \Countable
{
    use Traits\WhereTrait,
        Traits\HavingTrait;

    /**
     * @var Tuple
     */
    protected $columns;

    /**
     * @var Tuple
     */
    protected $tables;

    /**
     * @var Tuple
     */
    protected $groupings;

    /**
     * @var Tuple
     */
    protected $orderings;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var Tuple
     */
    protected $joins;

    /**
     * @param Fluorite $fluorite
     */
    public function __construct(Fluorite $fluorite)
    {
        parent::__construct($fluorite);

        $this->columns = new Tuple(Tuple::GLUE_COMMA);
        $this->tables  = new Tuple(Tuple::GLUE_COMMA);

        $this->groupings = new Tuple(Tuple::GLUE_COMMA);
        $this->orderings = new Tuple(Tuple::GLUE_COMMA);

        $this->joins = new Tuple(Tuple::GLUE_SPACE);

        $this->columns->addExpression(Column::autobox('*'));

        $this->initializeWhereCriteria();
        $this->initializeHavingCriteria();
    }

    /**
     * @param Expression[] ...$tables
     * @return $this
     */
    public function from(...$tables)
    {
        foreach ($tables as $table) {
            $this->tables->addExpression(Table::autobox($table));
        }

        return $this;
    }

    /**
     * @param Expression[] ...$columns
     * @return $this
     */
    public function select(...$columns)
    {
        foreach ($columns as $column) {
            $this->columns->addExpression(Column::autobox($column));
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function selectNothing()
    {
        $this->columns->clear();

        return $this;
    }

    /**
     * @param array ...$columns
     * @return $this
     */
    public function grouping(...$columns)
    {
        $this->groupings->addExpression(
            ...array_map([Column::class, 'autobox'], $columns)
        );

        return $this;
    }

    public function resetGrouping()
    {
        $this->groupings->clear();

        return $this;
    }

    /**
     * @param array ...$columns
     * @return $this
     */
    public function ordering(...$columns)
    {
        $this->orderings->addExpression(
            ...array_map([Order::class, 'autobox'], $columns)
        );

        return $this;
    }

    public function resetOrdering()
    {
        $this->orderings->clear();

        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = is_null($limit) ? $limit : (int) $limit;

        return $this;
    }

    /**
     * @param $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = is_null($offset) ? $offset : (int) $offset;

        return $this;
    }

    /**
     * @param $page
     * @param $perPage
     * @return $this
     */
    public function page($page, $perPage)
    {
        $this->offset(($page - 1) * $perPage)->limit($perPage);

        return $this;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function join(Expression $expression)
    {
        $this->joins->addExpression(new Join(Join::INNER_JOIN, $expression));

        return $this;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function leftJoin(Expression $expression)
    {
        $this->joins->addExpression(new Join(Join::LEFT_JOIN, $expression));

        return $this;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function rightJoin(Expression $expression)
    {
        $this->joins->addExpression(new Join(Join::LEFT_JOIN, $expression));

        return $this;
    }

    /**
     * @return Tuple
     */
    public function getJoins()
    {
        return $this->joins;
    }


    /**
     * @return Tuple
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return Tuple
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * @return Tuple
     */
    public function getGroupings()
    {
        return $this->groupings;
    }

    /**
     * @return Tuple
     */
    public function getOrderings()
    {
        return $this->orderings;
    }

    /**
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function count()
    {
        $clone = clone $this;

        $clone->limit(null);
        $clone->offset(null);
        $clone->resetOrdering();

        $clone->selectNothing()->select(fnc('COUNT', col('*')));

        $count = (int) $clone->column();

        return $count;
    }

    public function __clone()
    {
        $this->columns   = clone $this->columns;
        $this->tables    = clone $this->tables;
        $this->groupings = clone $this->groupings;
        $this->orderings = clone $this->orderings;
        $this->joins     = clone $this->joins;
        $this->where     = clone $this->where;
        $this->having    = clone $this->having;
    }
}