<?php

namespace Nerd2\Fluorite\ORM;

use Funky\Option\Option;
use Slang\ORM\Model;
use Slang\ORM\ModelCollection;

interface EntityManager
{
    /**
     * Creates and returns new model.
     *
     * @param Model $model
     * @param array $defaults
     * @return Model
     */
    public function create($model, array $defaults);

    /**
     * Finds entity using primary key.
     *
     * @param Model $model
     * @param $pk
     * @return Option
     */
    public function find($model, $pk);

    /**
     * Finds list of entities by primary keys.
     *
     * @param Model $model
     * @param array $pk
     * @return ModelCollection
     */
    public function findAll($model, array $pk);

    /**
     * Persists model changes if any.
     *
     * @param Model[] $models
     * @return $this
     */
    public function save(Model ...$models);

    /**
     * Deletes models from database.
     *
     * @param Model[] $models
     * @return $this
     */
    public function delete(Model ...$models);
}
