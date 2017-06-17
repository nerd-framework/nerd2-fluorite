<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 28.03.2016
 * @time 15:51
 */

namespace Nerd2\Fluorite\Builder\Traits;


use Nerd2\Fluorite\Expressions\Criteria;
use Nerd2\Fluorite\Expressions\Expression;

trait HavingTrait
{
    /**
     * @var Criteria
     */
    private $having;

    public function initializeHavingCriteria()
    {
        $this->having = new Criteria();
    }

    /**
     * @return Criteria
     */
    public function getHavingCriteria()
    {
        return $this->having;
    }

    /**
     * @param Expression $expression
     * @return $this
     */
    public function having(Expression $expression)
    {
        $this->having->where($expression);

        return $this;
    }


    /**
     * @param Expression $expression
     * @return $this
     */
    public function orHaving(Expression $expression)
    {
        $this->having->orWhere($expression);

        return $this;
    }
}