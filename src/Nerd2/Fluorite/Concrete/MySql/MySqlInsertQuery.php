<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 29.03.2016
 * @time 10:08
 */

namespace Nerd2\Fluorite\Concrete\MySql;


use Nerd2\Fluorite\Builder\InsertQuery;
use Nerd2\Fluorite\Concrete\MySqlExpressionVisitor;
use Nerd2\Fluorite\Expressions\Base\Constant;
use Nerd2\Fluorite\Expressions\Base\Tuple;
use Nerd2\Fluorite\Expressions\Brackets;
use Nerd2\Fluorite\QueryCloak;

class MySqlInsertQuery extends InsertQuery
{

    /**
     * @return QueryCloak
     */
    protected function buildQuery()
    {
        $visitor = new MySqlExpressionVisitor();

        $expression = new Tuple(

            Tuple::GLUE_SPACE,

            Constant::fromString(MySqlExpressionVisitor::KEYWORD_INSERT), $this->getTable()

        );

        if (count($this->getColumns()) > 0 && count($this->getValues()) > 0) {
            $expression->addExpression(
                new Brackets($this->getColumns()),
                Constant::fromString(MySqlExpressionVisitor::KEYWORD_VALUES),
                new Brackets($this->getValues())
            );
        }

        $expression->accept($visitor);

        return $visitor->getQueryCloak();
    }
}