<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.2016
 * Time: 12:42
 */

namespace Nerd2\Fluorite\ORM\Relations;

use Kote\Fluorite\Builder\SelectQuery;
use function Kote\Fluorite\column;
use function Kote\Fluorite\table;
use function Lambda\l;
use Nerd2\Fluorite\ORM\ModelCollection;
use Nerd2\Fluorite\ORM\Model;
use Nerd2\Fluorite\ORM\ModelWithRelations;

class ManyToMany extends Relation
{
    /**
     * @var Model
     */
    private $thisModel;

    private $pivotTable;
    private $thisModelKey;
    private $relatedModelKey;

    public function __construct($relatedTable, ModelWithRelations $thisModel, $pivotTable = null, $thisModelKey = null, $relatedModelKey)
    {
        $this->setRelatedModel($relatedTable);
        $this->setThisModel($thisModel);

        $this->setPivotTable($pivotTable);

        $this->setThisModelKey($thisModelKey);
        $this->setRelatedModelKey($relatedModelKey);
    }

    /**
     * @return string
     */
    public function getThisModelKey()
    {
        return $this->thisModelKey;
    }

    /**
     * @param string $thisModelKey
     */
    public function setThisModelKey($thisModelKey = null)
    {
        $this->thisModelKey = $thisModelKey ?: $this->getDefaultThisModelKey();
    }

    /**
     * @return string
     */
    public function getRelatedModelKey()
    {
        return $this->relatedModelKey;
    }

    /**
     * @param string $relatedModelKey
     */
    public function setRelatedModelKey($relatedModelKey = null)
    {
        $this->relatedModelKey = $relatedModelKey ?: $this->getDefaultRelatedModelKey();
    }

    /**
     * @param ModelWithRelations $sourceModel
     */
    public function setThisModel($sourceModel)
    {
        $this->thisModel = $sourceModel;
    }

    /**
     * @return Model
     */
    public function getThisModel()
    {
        return $this->thisModel;
    }

    /**
     * @param string $pivotTable
     */
    public function setPivotTable($pivotTable = null)
    {
        $this->pivotTable = $pivotTable ?: $this->getDefaultPivotTable();
    }

    /**
     * @return mixed
     */
    public function getPivotTable()
    {
        return $this->pivotTable;
    }


    public function load()
    {
        $relatedTable = $this->getRelatedModel();

        return $relatedTable::getListUsingCallback(function (SelectQuery $query) use ($relatedTable) {
            $query->selectNothing();
            $query->select($relatedTable::getTableName().".*");
            $query->join(
                table($this->getPivotTable())
                    ->on(column($this->getRelatedModelKey())
                        ->eq(column($relatedTable::getFullPrimaryKey())))
            );
            $query->filter($this->getThisModelKey(), $this->getThisModel()->id());
        });
    }

    public function applyToCollection(ModelCollection $modelCollection, $relation, $rest = null)
    {
        return;

        if (count($modelCollection) == 0) {
            return ;
        }

        $model = $this->getRelatedModel();
        $relatedTable = $this->getRelatedModel();

        $relatedIds = $modelCollection->map(l('$->id()'))->unique()->asArray();

        $objects = $model::getListUsingCallback(function (SelectQuery $query) use ($model, $relatedIds, $relatedTable) {
            $query->selectNothing();
            $query->select($relatedTable::getTableName().".*");
            $query->join(
                table($this->getPivotTable())
                    ->on(column($this->getRelatedModelKey())
                        ->eq(column($relatedTable::getFullPrimaryKey())))
            );
            $query->filter($this->getThisModelKey(), $relatedIds);
        });

        if ($rest) {
            $objects->with($rest);
        }

        $objectsWithKeys = [];

        foreach ($objects as $object) {
            $objectsWithKeys[ $object->id() ] = $object;
        }

        /**
         * @var Model $model
         */
        foreach ($modelCollection as $model)
        {
            $data = $objectsWithKeys[ $model->{$this->getRelatedField()} ];
            $model->setRelation($relation, $data);
        }
    }

    /**
     * @return string
     * @throws \Nerd2\Fluorite\ORM\Exceptions\ModelException
     */
    private function getDefaultPivotTable()
    {
        $relatedTable = $this->getRelatedModel();

        return $this->thisModel->getTableName() . "_" . $relatedTable::getTableName() . "_link";
    }

    /**
     * @return string
     * @throws \Nerd2\Fluorite\ORM\Exceptions\ModelException
     */
    private function getDefaultThisModelKey()
    {
        return $this->thisModel->getTableName() . "_id";
    }

    /**
     * @return string
     * @throws \Nerd2\Fluorite\ORM\Exceptions\ModelException
     */
    private function getDefaultRelatedModelKey()
    {
        $relatedModel = $this->getRelatedModel();

        return $relatedModel::getTableName() . "_id";
    }
}