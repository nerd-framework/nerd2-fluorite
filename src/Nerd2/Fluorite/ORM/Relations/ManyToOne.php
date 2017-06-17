<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.2016
 * Time: 12:40
 */

namespace Nerd2\Fluorite\ORM\Relations;


use Kote\Fluorite\Builder\SelectQuery;
use Nerd2\Fluorite\ORM\Model;
use Nerd2\Fluorite\ORM\Collections\ModelCollection;
use Nerd2\Fluorite\ORM\ModelWithRelations;

class ManyToOne extends Relation
{
    /**
     * @var Model
     */
    private $sourceModel;

    /**
     * @var null
     */
    private $foreignKey;

    /**
     * ManyToOne constructor.
     *
     * @param string $relatedModel
     * @param ModelWithRelations $sourceModel
     * @param null $foreignKey
     */
    public function __construct($relatedModel, ModelWithRelations $sourceModel, $foreignKey = null)
    {
        $this->setRelatedModel($relatedModel);
        $this->sourceModel = $sourceModel;
        $this->foreignKey = $foreignKey;
        $this->setRelatedField($this->foreignKey ?: $relatedModel::getTableName()."_id");
    }

    /**
     * @return Model
     */
    public function load()
    {
        $relatedTable = $this->getRelatedModel();

        return $relatedTable::find($this->sourceModel->{$this->getRelatedField()})->getOrElse(null);
    }

    public function applyToCollection(ModelCollection $modelCollection, $relation, $rest = null)
    {
        if (count($modelCollection) == 0) {
            return ;
        }

        $model = $this->getRelatedModel();

        $relatedIds = $modelCollection->map(function ($model) {
            return $model->{$this->getRelatedField()};
        })->unique()->asArray();

        $objects = $model::getListUsingCallback(function (SelectQuery $query) use ($model, $relatedIds) {
            $query->filter($model::getPrimaryKey(), $relatedIds);
        });

        if ($rest) {
            $objects->with($rest);
        }

        $objectsWithKeys = [];

        foreach ($objects as $object) {
            $objectsWithKeys[$object->id()] = $object;
        }

        /**
         * @var Model $model
         */
        foreach ($modelCollection as $model)
        {
            if ($model->{$this->getRelatedField()} && array_key_exists($model->{$this->getRelatedField()}, $objectsWithKeys)) {
                $data = $objectsWithKeys[$model->{$this->getRelatedField()}];
                $model->setRelation($relation, $data);
            }
        }
    }
}