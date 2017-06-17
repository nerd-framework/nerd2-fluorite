<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 05.05.2016
 * @time 10:39
 */

namespace Nerd2\Fluorite\ORM\Collections;

use Nerd2\Fluorite\ORM\Model;
use Nerd2\Fluorite\ORM\Relations\Relation;
use Nerd2\Fluorite\ORM\Collections\Collection;

class ModelCollection extends Collection
{
    public function contains(Model $that)
    {
        foreach ($this as $el) {
            if ($el instanceof $that && $el->id() == $that->id()) {
                return true;
            }
        }
        return false;
    }

    private function withRelation($relation)
    {
        if (count($this) == 0) {
            return ;
        }

        $rel = explode(".", $relation, 2);

        if (count($rel) == 2) {
            list($relation, $rest) = $rel;
        } else {
            $rest = null;
        }

        $first = $this[0];

        $reflection = new \ReflectionMethod($first, $relation);
        $reflection->setAccessible(true);

        /**
         * @var $relationObject Relation
         */
        $relationObject = $reflection->invoke($first);

        $relationObject->applyToCollection($this, $relation, $rest);
    }

    /**
     * @param array ...$relations
     * @return $this
     */
    public function with(...$relations)
    {
        foreach ($relations as $relation) {
            $this->withRelation($relation);
        }

        return $this;
    }

    /**
     * Returns list of ids of models in collection
     * @return array
     */
    public function ids()
    {
        if (count($this) == 0) {
            return [];
        }

        $acc = [];
        foreach ($this as $model) {
            $acc[] = $model->id();
        }

        return $acc;
    }
}