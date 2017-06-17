<?php

namespace Nerd2\Fluorite\ORM\Relations;

use Nerd2\Fluorite\ORM\Model;
use Nerd2\Fluorite\ORM\ModelCollection;

abstract class Relation
{
    /**
     * @var Model
     */
    private $relatedModel;

    /**
     * @var string
     */
    private $relatedField;

    /**
     * @return Model
     */
    public function getRelatedModel()
    {
        return $this->relatedModel;
    }

    /**
     * @return string
     */
    public function getRelatedField()
    {
        return $this->relatedField;
    }

    /**
     * @param string $relatedModel
     */
    public function setRelatedModel($relatedModel)
    {
        $this->relatedModel = $relatedModel;
    }

    /**
     * @param string $relatedField
     */
    public function setRelatedField($relatedField)
    {
        $this->relatedField = $relatedField;
    }

    abstract public function load();

    abstract public function applyToCollection(ModelCollection $modelCollection, $relation, $rest = null);
}
