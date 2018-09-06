<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\IndexManager;
use Illuminate\Support\Collection as BaseCollection;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine as ScoutEngine;

final class Engine extends ScoutEngine
{
    /**
     * @var IndexManager
     */
    private $indexManager;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var array
     */
    private $config;

    /**
     * @param IndexManager $indexManager
     * @param DocumentManager $documentManager
     * @param array $config
     */
    public function __construct(
        IndexManager $indexManager,
        DocumentManager $documentManager,
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
