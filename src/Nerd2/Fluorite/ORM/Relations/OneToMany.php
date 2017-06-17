<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.2016
 * Time: 12:39
 */

namespace Nerd2\Fluorite\ORM\Relations;


use Kote\Fluorite\Builder\SelectQuery;
use Nerd2\Fluorite\ORM\Model;
use Nerd2\Fluorite\ORM\ModelCollection;
use Nerd2\Fluorite\ORM\ModelWithRelations;

class OneToMany extends Relation
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
     * OneToMany constructor.
     * @param string $relatedTable
     * @param ModelWithRelations $sourceModel
     * @param null $foreignKey
     */
    public function __construct($relatedTable, ModelWithRelations $sourceModel, $foreignKey = null)
    {
        $this->setRelatedModel($relatedTable);
        $this->setRelatedField($foreignKey ?: $sourceModel::getTableName()."_id");

        $this->sourceModel = $sourceModel;
        $this->foreignKey = $foreignKey;
    }

    public function load()
    {
        $relatedTable = $this->getRelatedModel();

        return $relatedTable::getListUsingCallback(function (SelectQuery $query) {
                $query->filter($this->getRelatedField(), $this->sourceModel->id());
        });
    }

    public function applyToCollection(ModelCollection $modelCollection, $relation, $rest = null)
    {
        throw new \InvalidArgumentException("Unsupported operation");
    }
}