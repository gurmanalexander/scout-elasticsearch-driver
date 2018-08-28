<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\IndexManager as IndexManagerContract;
use Illuminate\Support\Collection;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine as ScoutEngine;

class Engine extends ScoutEngine
{
    /**
     * @var IndexManagerContract
     */
    private $indexManager;

    /**
     * @var DocumentManagerContract
     */
    private $documentManager;

    /**
     * @param IndexManagerContract $indexManager
     * @param DocumentManagerContract $documentManager
     */
    public function __construct(
        IndexManagerContract $indexManager,
        DocumentManagerContract $documentManager
    ) {
        $this->indexManager = $indexManager;
        $this->documentManager = $documentManager;
    }

    /**
     * @inheritdoc
     */
    public function update($models)
    {
        $models->toSearchableDocumentsGroupedByIndex()
            ->each(function (Collection $documents) {
                $index = $documents->first()->getSearchableIndex();
                $this->documentManager->index($index, $documents);
            });
    }

    /**
     * @inheritdoc
     */
    public function delete($models)
    {
        $models->toSearchableDocumentsGroupedByIndex()
            ->each(function (Collection $documents) {
                $index = $documents->first()->getSearchableIndex();
                $this->documentManager->delete($index, $documents);
            });
    }

    /**
     * @inheritdoc
     */
    public function search(Builder $builder)
    {
        // TODO: Implement search() method.
    }

    /**
     * @inheritdoc
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        // TODO: Implement paginate() method.
    }

    /**
     * @inheritdoc
     */
    public function mapIds($results)
    {
        // TODO: Implement mapIds() method.
    }

    /**
     * @inheritdoc
     */
    public function map($results, $model)
    {
        // TODO: Implement map() method.
    }

    /**
     * @inheritdoc
     */
    public function getTotalCount($results)
    {
        // TODO: Implement getTotalCount() method.
    }
}
