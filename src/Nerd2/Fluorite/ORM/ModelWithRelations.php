<?php


namespace Nerd2\Fluorite\ORM;


use Nerd2\Fluorite\ORM\Relations\Relation;

class ModelWithRelations extends ActiveModel
{
    /**
     * @var Model[]
     */
    protected $relations = [];

    /**
     * @var Relation[]
     */
    protected $relationObjects = [];

    /**
     * @return string
     */
    private function getCallerFunction()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        return end($trace)["function"];
    }

    /**
     * @param string $model
     * @param null $foreignKey
     * @return Relation
     */
    protected function hasMany($model, $foreignKey = null)
    {
        $caller = $this->getCallerFunction();

        if (! array_key_exists($caller, $this->relationObjects)) {
            $this->relationObjects[$caller] = new Relations\OneToMany($model, $this, $foreignKey);
        }

        return $this->relationObjects[$caller];
    }

    /**
     * @param string $model
     * @param null $foreignKey
     * @return Relation
     */
    protected function belongsTo($model, $foreignKey = null)
    {
        $caller = $this->getCallerFunction();

        if (! array_key_exists($caller, $this->relationObjects)) {
            $this->relationObjects[$caller] = new Relations\ManyToOne($model, $this, $foreignKey);
        }

        return $this->relationObjects[$caller];
    }

    /**
     * @param string $model
     * @return Relations\Relation
     */
    protected function hasOne($model)
    {
        $caller = $this->getCallerFunction();

        if (! array_key_exists($caller, $this->relationObjects)) {
            $this->relationObjects[$caller] = new Relations\OneToOne($model, $this);
        }

        return $this->relationObjects[$caller];
    }

    /**
     * @param string $model
     * @param null $pivot
     * @param null $thisPk
     * @param null $thatPk
     * @return Relation
     */
    protected function belongsToMany($model, $pivot = null, $thisPk = null, $thatPk = null)
    {
        $caller = $this->getCallerFunction();

        if (! array_key_exists($caller, $this->relationObjects)) {
            $this->relationObjects[$caller] = new Relations\ManyToMany($model, $this, $pivot, $thisPk, $thatPk);
        }

        return $this->relationObjects[$caller];
    }

    /**
     * @param string $relation
     * @return Model
     */
    protected function getRelation($relation)
    {
        if (!array_key_exists($relation, $this->relations)) {
            $this->loadRelation($relation);
        }

        return $this->relations[$relation];
    }

    /**
     * @param string $relation
     */
    private function loadRelation($relation)
    {
        if (!method_exists($this, $relation)) {
            throw new \InvalidArgumentException("Attribute $relation does not exist.");
        }

        $relationObject = $this->$relation();

        if (!$relationObject instanceof Relation) {
            throw new \InvalidArgumentException("Could not load relation $relation.");
        }

        $this->setRelation($relation, $relationObject->load());
    }

    public function setRelation($relation, $data)
    {
        $this->relations[$relation] = $data;
    }
}