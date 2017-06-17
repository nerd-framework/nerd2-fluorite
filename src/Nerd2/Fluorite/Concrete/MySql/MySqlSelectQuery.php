<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 26.03.16
 * @time 19:59
 */

namespace Nerd2\Fluorite\Concrete\MySql;


use Nerd2\Fluorite\Builder\SelectQuery;
use Nerd2\Fluorite\Concrete\MySqlExpressionVisitor;
use Nerd2\Fluorite\Expressions\Base\Column;
use Nerd2\Fluorite\Expressions\Base\Constant;
use Nerd2\Fluorite\Expressions\Base\Couple;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\Expressions\Base\Value;
use Nerd2\Fluorite\Expressions\FunctionExpression;

class MySqlSelectQuery extends SelectQuery
{
    public function fulltext(array $columns, $keywords)
    {
        $cols = array_map([Column::class, "autobox"], $columns);
        $kw = Value::autobox($keywords);

        $match = new FunctionExpression('MATCH', ...$cols);
        $against = new FunctionExpression('AGAINST', $kw->in(Constant::fromString('BOOLEAN MODE')));

        $this->where(new Couple($match, $against));

        return $this;
    }

    /**
     * @return \Nerd2\Fluorite\QueryCloak
     */
    protected function buildQuery()
    {
        $visitor = new MySqlExpressionVisitor();

        $expression = new Tuple(

            Tuple::GLUE_SPACE,

            Constant::fromString(MySqlExpressionVisitor::KEYWORD_SELECT), $this->getColumns(),
            Constant::fromString(MySqlExpressionVisitor::KEYWORD_FROM),   $this->getTables()
            
        );
        
        if (count($this->getJoins()) > 0) {
            $expression->addExpression(...$this->getJoins());
        }

        if ($this->getWhereCriteria()->hasExpression()) {
            $expression->addExpression(
                Constant::fromString(MySqlExpressionVisitor::KEYWORD_WHERE),
                $this->getWhereCriteria()
            );
        }

        if (count($this->getGroupings()) > 0) {
            $expression->addExpression(
                Constant::fromString(MySqlExpressionVisitor::KEYWORD_GROUP_BY),
                $this->getGroupings()
            );
        }

        if ($this->getHavingCriteria()->hasExpression()) {
            $expression->addExpression(
                Constant::fromString(MySqlExpressionVisitor::KEYWORD_HAVING),
                $this->getHavingCriteria()
            );
        }

        if (count($this->getOrderings()) > 0) {
            $expression->addExpression(
                Constant::fromString(MySqlExpressionVisitor::KEYWORD_ORDER_BY),
                $this->getOrderings()
            );
        }

        if (! is_null($this->getLimit())) {
            $expression->addExpression(new Couple(
                Constant::fromString(MySqlExpressionVisitor::KEYWORD_LIMIT),
                Constant::fromString($this->getLimit())
            ));
        }

        if (! is_null($this->getOffset())) {
            $expression->addExpression(new Couple(
                Constant::fromString(MySqlExpressionVisitor::KEYWORD_OFFSET),
                Constant::fromString($this->getOffset())
            ));
        }

        $expression->accept($visitor);

        return $visitor->getQueryCloak();
    }
}