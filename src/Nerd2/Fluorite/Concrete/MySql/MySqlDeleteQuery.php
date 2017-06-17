<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 28.04.16
 * @time 10:21
 */

namespace Nerd2\Fluorite\Concrete\MySql;


use Nerd2\Fluorite\Builder\DeleteQuery;
use Nerd2\Fluorite\Concrete\MySqlExpressionVisitor;
use Nerd2\Fluorite\Expressions\Base\Constant;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\FluoriteException;
use Nerd2\Fluorite\QueryCloak;

class MySqlDeleteQuery extends DeleteQuery
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
            Constant::fromString(MySqlExpressionVisitor::KEYWORD_DELETE),
            Constant::fromString(MySqlExpressionVisitor::KEYWORD_FROM),
            $this->getTable()
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