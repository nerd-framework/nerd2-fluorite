<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.04.16
 * @time 14:38
 */

namespace Nerd2\Fluorite\Concrete\MySql;


use Nerd2\Fluorite\Builder\UpdateQuery;
use Nerd2\Fluorite\Concrete\MySqlExpressionVisitor;
use Nerd2\Fluorite\Expressions\Base\Column;
use Nerd2\Fluorite\Expressions\Base\Constant;
use Nerd2\Fluorite\Expressions\Base\Couple;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\Expressions\Base\Value;
use Nerd2\Fluorite\Expressions\FunctionExpression;
use Nerd2\Fluorite\FluoriteException;
use Nerd2\Fluorite\QueryCloak;

class MySqlUpdateQuery extends UpdateQuery
{

    /**
     * @return QueryCloak
     * @throws FluoriteException
     */
    protected function buildQuery()
    {
        $visitor = new MySqlExpressionVisitor();

        $expression = new Tuple(
            Tuple::GLUE_SPACE,
            Constant::fromString(MySqlExpressionVisitor::KEYWORD_UPDATE),
            $this->getTable()
        );

        if (count($this->getUpdates()) == 0) {
            throw new FluoriteException("Nothing to update");
        }

        $expression->addExpression(
            Constant::fromString(MySqlExpressionVisitor::KEYWORD_SET),
            $this->getUpdates()
        );

        if (!$this->getWhereCriteria()->hasExpression()) {
            throw new FluoriteException("WHERE section must be specified");
        }

        $expression->addExpression(
            Constant::fromString(MySqlExpressionVisitor::KEYWORD_WHERE),
            $this->getWhereCriteria()
        );

        $expression->accept($visitor);

        return $visitor->getQueryCloak();
    }

    /**
     * @param array $columns
     * @param string $keywords
     * @return $this
     */
    public function fulltext(array $columns, $keywords)
    {
        throw new \BadMethodCallException;
    }
}