<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 28.03.2016
 * @time 12:13
 */

namespace Nerd2\Fluorite\Expressions\Base\Traits;


use Kote\Fluorite\Expressions\Base\Comparison;
use Nerd2\Fluorite\Expressions\Base\Value;

/**
 * Class ComparisonTrait
 * @package Slang\Database\Fluorite\Expressions\Base\Traits
 *
 * @method Comparison eq($that)
 * @method Comparison nullSafeEqual($that)
 * @method Comparison ne($that)
 * @method Comparison less($that)
 * @method Comparison greater($that)
 * @method Comparison lessOrEqual($that)
 * @method Comparison greaterOrEqual($that)
 * @method Comparison in($that)
 * @method Comparison like($that)
 * @method Comparison is($that)
 * @method Comparison isNot($that)
 * @method Comparison on($that)
 * @method Comparison using($that)
 */
trait ComparisonTrait
{
    /**
     * @var array Method names to Comparison operator constant mappings.
     */
    private static $constantMapping = [
        'eq'                => Comparison::EQ,
        'nullSafeEqual'     => Comparison::NSEQ,
        'ne'                => Comparison::NEQ,
        'less'              => Comparison::LT,
        'greater'           => Comparison::GT,
        'lessOrEqual'       => Comparison::LTE,
        'greaterOrEqual'    => Comparison::GTE,
        'in'                => Comparison::IN,
        'like'              => Comparison::LIKE,
        'is'                => Comparison::IS,
        'isNot'             => Comparison::IS_NOT,
        'on'                => Comparison::ON,
        'using'             => Comparison::USING
    ];

    /**
     * @param $name
     * @param $argument
     * @return Comparison
     */
    public function __call($name, $argument)
    {
        if (! isset(self::$constantMapping[$name])) {
            trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
        }

        if (count($argument) == 0) {
            throw new \InvalidArgumentException('Target expression is not specified.');
        }

        $that = array_shift($argument);

        return new Comparison($this, self::$constantMapping[$name], Value::autobox($that));
    }
}