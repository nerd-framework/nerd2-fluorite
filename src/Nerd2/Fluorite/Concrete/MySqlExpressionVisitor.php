<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 15:56
 */

namespace Nerd2\Fluorite\Concrete;


use Nerd2\Fluorite\Expressions;
use Nerd2\Fluorite\Expressions\Base;
use Nerd2\Fluorite\Expressions\Base\Aliased;
use Nerd2\Fluorite\FluoriteException;
use Nerd2\Fluorite\QueryCloak;

class MySqlExpressionVisitor extends Expressions\ExpressionVisitor
{
    const PLACEHOLDER               = '?';
    const COLUMN_WILDCARD           = '*';
    const TABLE_COLUMN_SEPARATOR    = '.';
    const TABLE_COLUMN_QUOTE        = '`';

    const CHAR_SPACE                = ' ';
    const CHAR_COMMA                = ',';
    const CHAR_OPEN_BRACKET         = '(';
    const CHAR_CLOSE_BRACKET        = ')';

    const KEYWORD_SELECT            = 'SELECT';
    const KEYWORD_UPDATE            = 'UPDATE';
    const KEYWORD_INSERT            = 'INSERT INTO';
    const KEYWORD_DELETE            = 'DELETE';
    const KEYWORD_GROUP_BY          = 'GROUP BY';
    const KEYWORD_FROM              = 'FROM';
    const KEYWORD_WHERE             = 'WHERE';
    const KEYWORD_HAVING            = 'HAVING';
    const KEYWORD_ORDER_BY          = 'ORDER BY';
    const KEYWORD_SET               = 'SET';
    const KEYWORD_LIMIT             = 'LIMIT';
    const KEYWORD_OFFSET            = 'OFFSET';
    const KEYWORD_AS                = 'AS';
    const KEYWORD_VALUES            = 'VALUES';
    const KEYWORD_JOIN              = 'JOIN';

    /**
     * @var QueryCloak
     */
    private $queryCloak;

    /**
     * MySqlExpressionVisitor constructor.
     */
    public function __construct()
    {
        $this->queryCloak = new QueryCloak();
    }

    /**
     * @return QueryCloak
     */
    public function getQueryCloak()
    {
        return $this->queryCloak;
    }

    /**
     * @param Expressions\Base\Column $column
     */
    public function visitColumn(Expressions\Base\Column $column)
    {
        if ($column->getDatabase()) {
            $this->drawWithQuotes($column->getDatabase());
            $this->draw(self::TABLE_COLUMN_SEPARATOR);
        }

        if ($column->getTable()) {
            $this->drawWithQuotes($column->getTable());
            $this->draw(self::TABLE_COLUMN_SEPARATOR);
        }

        $this->drawColumnName($column->getColumn());

        if ($column->getAlias()) {
            $this->drawPaddedKeyword(self::KEYWORD_AS);
            $this->drawWithQuotes($column->getAlias());
        }
    }

    /**
     * @param \Nerd2\Fluorite\Expressions\Base\Table $table
     */
    public function visitTable(Expressions\Base\Table $table)
    {
        if ($table->getDatabase()) {
            $this->drawWithQuotes($table->getDatabase());
            $this->draw(self::TABLE_COLUMN_SEPARATOR);
        }

        if ($table->getTable()) {
            $this->drawWithQuotes($table->getTable());
        }

        if ($table->getAlias()) {
            $this->draw(self::CHAR_SPACE);
            $this->drawWithQuotes($table->getAlias());
        }
    }

    /**
     * @param \Nerd2\Fluorite\Expressions\Base\Value $value
     */
    public function visitValue(Expressions\Base\Value $value)
    {
        $this->putValue($value->getValue())->draw(self::PLACEHOLDER);
    }

    /**
     * @param \Nerd2\Fluorite\Expressions\Base\Constant $constant
     */
    public function visitConstant(Expressions\Base\Constant $constant)
    {
        $this->draw($constant->getValue());
    }

    /**
     * @param Base\Couple $couple
     */
    public function visitCouple(Base\Couple $couple)
    {
        $couple->getFirst()->accept($this);

        if ($second = $couple->getSecond()) {
            $this->draw(self::CHAR_SPACE);
            $couple->getSecond()->accept($this);
        }
    }

    /**
     * @param \Nerd2\Fluorite\Expressions\Base\Comparison $comparison
     */
    public function visitComparison(Base\Comparison $comparison)
    {
        $comparison->getLeft()->accept($this);
        $this->drawPaddedKeyword($comparison->getOperator());
        $comparison->getRight()->accept($this);
    }

    /**
     * @param \Nerd2\Fluorite\Expressions\Base\CompositeExpression $compositeExpression
     * @throws FluoriteException
     */
    public function visitCompositeExpression(Expressions\Base\CompositeExpression $compositeExpression)
    {
        $expressions = $compositeExpression->getExpressionList();

        if (count($expressions) == 0) {
            throw new FluoriteException("Must be at least one expression in composition.");
        }

        array_shift($expressions)->accept($this);

        foreach ($expressions as $expression) {
            $this->drawPaddedKeyword($compositeExpression->getConjunctionType());
            $expression->accept($this);
        }
    }

    /**
     * @param Expressions\Criteria $criteria
     */
    public function visitCriteria(Expressions\Criteria $criteria)
    {
        if ($criteria->hasExpression()) {
            $criteria->getExpression()->accept($this);
        }
    }

    /**
     * @param Expressions\Order $order
     */
    public function visitOrder(Expressions\Order $order)
    {
        $order->getExpression()->accept($this);
        $this->draw(self::CHAR_SPACE);
        $this->draw($order->getDirection());
    }

    /**
     * @param Expressions\Brackets $brackets
     */
    public function visitBrackets(Expressions\Brackets $brackets)
    {
        $this->draw(self::CHAR_OPEN_BRACKET);

        $brackets->getExpression()->accept($this);

        $this->draw(self::CHAR_CLOSE_BRACKET);
    }

    /**
     * @param Expressions\Values $values
     * @throws FluoriteException
     */
    public function visitValues(Expressions\Values $values)
    {
        $expressions = $values->getExpressionList();

        if (count($expressions) == 0) {
            throw new FluoriteException("Empty value list.");
        }

        $this->draw(self::CHAR_OPEN_BRACKET);

        array_shift($expressions)->accept($this);

        foreach ($expressions as $expression) {
            $this->draw(',');
            $expression->accept($this);
        }

        $this->draw(self::CHAR_CLOSE_BRACKET);
    }

    /**
     * @param Expressions\FunctionExpression $functionExp
     */
    public function visitFunctionExpression(Expressions\FunctionExpression $functionExp)
    {
        $this->draw(strtoupper($functionExp->getFunction()));

        $this->draw(self::CHAR_OPEN_BRACKET);

        $functionExp->getExpression()->accept($this);

        $this->draw(self::CHAR_CLOSE_BRACKET);
    }

    /**
     * @param Expressions\Base\Tuple $tuple
     * @throws FluoriteException
     */
    public function visitTuple(Expressions\Base\Tuple $tuple)
    {
        $expressions = $tuple->getExpressionList();

        if (count($expressions) == 0) {
            //throw new FluoriteException("Tuple is empty.");
            return;
        }

        array_shift($expressions)->accept($this);

        foreach ($expressions as $expression) {
            $this->draw($tuple->getGlue());
            $expression->accept($this);
        }
    }

    /**
     * @param Aliased $aliased
     */
    public function visitAliasedExpression(Aliased $aliased)
    {
        $aliased->getExpression()->accept($this);

        $this->drawPaddedKeyword('AS');

        $this->draw('`');
        $this->draw($aliased->getAlias());
        $this->draw('`');
    }

    /**
     * @param Base\Raw $raw
     */
    public function acceptRaw(Base\Raw $raw)
    {
        $this->draw($raw->getExpression());

        foreach ($raw->getParameters() as $key => $value) {
            $this->putValue($value, is_string($key) ? $key : null);
        }
    }

    /**
     * @param Base\Join $join
     */
    public function acceptJoin(Base\Join $join)
    {
        $this->draw($join->getJoinType());
        $this->draw(self::CHAR_SPACE);
        $this->draw(self::KEYWORD_JOIN);
        $this->draw(self::CHAR_SPACE);
        $join->getExpression()->accept($this);
    }


    /**
     * @param $column
     * @return string
     */
    private function drawColumnName($column)
    {
        if ($column == self::COLUMN_WILDCARD) {
            $this->draw($column);
        } else {
            $this->drawWithQuotes($column);
        }
    }

    /**
     * @param $drawable
     * @return $this
     */
    private function draw($drawable)
    {
        $this->getQueryCloak()->appendSql($drawable);

        return $this;
    }

    /**
     * @param $keyword
     * @return $this
     */
    private function drawPaddedKeyword($keyword)
    {
        return $this->draw(self::CHAR_SPACE)->draw($keyword)->draw(self::CHAR_SPACE);
    }

    /**
     * @param string $string
     * @return $this
     */
    private function drawWithQuotes($string)
    {
        return $this->draw(self::TABLE_COLUMN_QUOTE)->draw($string)->draw(self::TABLE_COLUMN_QUOTE);
    }

    /**
     * @param $value
     * @param null|string $key
     * @return $this
     */
    private function putValue($value, $key = null)
    {
        $this->getQueryCloak()->putValue($value, $key);
        return $this;
    }
}