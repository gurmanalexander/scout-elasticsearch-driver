<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\IndexManager as IndexManagerContract;
use Illuminate\Support\Collection as BaseCollection;
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
     * @var array
     */
    private $config;

    /**
     * @param IndexManagerContract $indexManager
     * @param DocumentManagerContract $documentManager
     * @param array $config
     */
    public function __construct(
        IndexManagerContract $indexManager,
        DocumentManagerContract $documentManager,
        array $config = []
    ) {
        $this->indexManager = $indexManager;
        $this->documentManager = $documentManager;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function update($models)
    {
        $config = $this->config;

        $indices = $models->getSearchableIndices();
        $documents = $models->toSearchableDocuments();

        $documents->each(function (BaseCollection $indexDocuments, string $indexName) use ($indices, $config) {
            $index = $indices->get($indexName);

            $this->documentManager
                ->index($index, $indexDocuments, $config['force_document_refresh']);
        });
    }

    /**
     * @inheritdoc
     */
    public function delete($models)
    {
        $config = $this->config;

        $indices = $models->getSearchableIndices();
        $documents = $models->toSearchableDocuments();

        $documents->each(function (BaseCollection $indexDocuments, string $indexName) use ($indices, $config) {
            $index = $indices->get($indexName);

            $this->documentManager
                ->delete($index, $indexDocuments, $config['force_document_refresh']);
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
    public function map(Builder $builder, $results, $model)
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
