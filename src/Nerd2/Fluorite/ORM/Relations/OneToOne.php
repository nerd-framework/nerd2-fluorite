<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.2016
 * Time: 12:41
 */

namespace Nerd2\Fluorite\ORM\Relations;


use Nerd2\Fluorite\ORM\Collections\ModelCollection;

class OneToOne extends Relation
{
    public function load()
    {
        // TODO: Implement load() method.
    }

    public function applyToCollection(ModelCollection $modelCollection, $relation, $rest = null)
    {
        throw new \InvalidArgumentException("Unsupported operation");
    }
}