<?php

namespace Nerd2\Fluorite\ORM\Concrete;

use Funky\Option\Option;
use Nerd2\Fluorite\Factory\Fluorite;
use Nerd2\Fluorite\ORM\EntityManager;
use Nerd2\Fluorite\ORM\Model;
use Nerd2\Fluorite\ORM\ModelCollection;

class MysqlEntityManager implements EntityManager
{
    /**
     * @var Fluorite
     */
    private $fluorite;

    /**
     * @param Fluorite $fluorite
     */
    public function __construct(Fluorite $fluorite)
    {
        $this->fluorite = $fluorite;
    }

    /**
     * @return Fluorite
     */
    public function getFluorite()
    {
        return $this->fluorite;
    }

    /**
     * @param Model $model
     * @param array $defaults
     * @return Model
     */
    public function create($model, array $defaults)
    {
        $this->getFluorite()
             ->insertInto($model::getTableName())
             ->fill($defaults)
             ->execute();

        $pk = $this->getFluorite()->getConnection()->getLastInsertId();

        return $this->find($model, $pk)->get();
    }

    /**
     * Finds entity using primary key.
     *
     * @param Model $model
     * @param $pk
     * @return Option
     */
    public function find($model, $pk)
    {
        return Option::wrap(
            $this->getFluorite()
                 ->selectFrom($model::getTableName())
                 ->filter($model::getPrimaryKey(), $pk)
                 ->first(),
            false
        );
    }

    /**
     * Returns list of entities by list of primary keys.
     *
     * @param Model $model
     * @param array $pk
     * @return ModelCollection
     */
    public function findAll($model, array $pk)
    {
        $rows = $this->getFluorite()
                     ->selectFrom($model::getTableName())
                     ->filter($model::getPrimaryKey(), $pk)
                     ->all();

        return new ModelCollection(
            array_map("$model::hydrate", $rows)
        );
    }

    /**
     * Persists model changes if any.
     *
     * @param Model[] $models
     * @return $this
     */
    public function save(Model ...$models)
    {
        foreach ($models as $model) {
            $changed = $model->getChangedAttributes();
            if (count($changed) > 0) {
                $this->getFluorite()
                     ->update($model->getTableName())
                     ->fill($changed)
                     ->execute();
                $model->syncChangedAttributes();
            }
        }
        return $this;
    }

    /**
     * Deletes models from database.
     *
     * @param Model[] $models
     * @return $this
     */
    public function delete(Model ...$models)
    {
        foreach ($models as $model) {
            $this->getFluorite()
                 ->delete($model->getTableName())
                 ->filter($model->getPrimaryKey(), $model->id())
                 ->execute();
        }
        return $this;
    }
}
