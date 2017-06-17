<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 15:56
 */

namespace Nerd2\Fluorite\Expressions\Base;


use Nerd2\Fluorite\Expressions\Expression;
use Nerd2\Fluorite\Expressions\ExpressionListContainer;
use Nerd2\Fluorite\Expressions\ExpressionVisitor;

class CompositeExpression extends ExpressionListContainer
{
    const CONJ_AND   = 'AND';
    const CONJ_OR    = 'OR';

    /**
     * @var array
     */
    private static $validConjunctions = [self::CONJ_AND, self::CONJ_OR];

    /**
     * @var string
     */
    private $conjunctionType;

    /**
     * @var Expression[]
     */
    private $expressions = [];

    /**
     * @param string $type
     * @param Expression[] $expressions
     *
     * @throws \RuntimeException
     */
    public function __construct($type, Expression ...$expressions)
    {
        $this->setConjunction($type);
        $this->addExpression(...$expressions);
    }

    /**
     * @param string $type
     */
    public function setConjunction($type)
    {
        if (! $this->isValidConjunction($type)) {
            throw new \RuntimeException("Invalid type of conjunction.");
        }

        $this->conjunctionType = $type;
    }

    /**
     * @param $type
     * @return bool
     */
    public static function isValidConjunction($type)
    {
        return in_array($type, self::$validConjunctions);
    }

    /**
     * @return string
     */
    public function getConjunctionType()
    {
        return $this->conjunctionType;
    }
    
    /**
     * @param ExpressionVisitor $visitor
     */
    public function accept(ExpressionVisitor $visitor)
    {
        $visitor->visitCompositeExpression($this);
    }

}