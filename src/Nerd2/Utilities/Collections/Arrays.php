<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 24.02.16
 * Time: 21:53
 */

namespace Nerd2\Utilities\Collections;

class Arrays {

    /**
     * Tests whether all elements in the $array pass the test
     * implemented by the provided $predicate function.
     *
     * @param array $array
     * @param callable $predicate
     * @return bool
     */
    public static function all(array $array, $predicate)
    {
        foreach ($array as $key => $value) {
            if (!$predicate($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Tests whether at least one element in the $array pass the test
     * implemented by the provided $predicate function.
     *
     * @param array $array
     * @param callable $predicate
     * @return bool
     */
    public static function any(array $array, $predicate)
    {
        foreach ($array as $key => $value) {
            if ($predicate($value, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns first element in the $array or $default value if array is empty.
     *
     * @param array $array
     * @param null $default
     * @return null
     */
    public static function first(array $array, $default = null)
    {
        return (count($array) == 0) ? $default : $array[0];
    }

}