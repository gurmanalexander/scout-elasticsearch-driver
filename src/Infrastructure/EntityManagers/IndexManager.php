<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Namespaces\IndicesNamespace as IndicesNamespaceContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\IndexManager as IndexManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;

class IndexManager implements IndexManagerContract
{
    /**
     * @var IndicesNamespaceContract
     */
    private $indices;

    /**
     * @param ClientContract $client
     */
    public function __construct(ClientContract $client)
    {
        $this->indices = $client->indices();
    }

    /**
     * @inheritdoc
     */
    public function exists(Index $index): bool
    {
        $payload = (new Payload())
            ->index($index->getName());

        return $this->indices
            ->exists($payload->toArray());
    }

    /**
     * @inheritdoc
     */
    public function create(Index $index): void
    {
        $settings = $index->getSettings();
        $mapping = $index->getMapping();

        $payload = (new Payload())
            ->index($index->getName());

        if ($settings->count() > 0) {
            $payload->body()
                ->settings($settings);
        }

        if ($mapping->count() > 0) {
            $payload->body()
                ->mappings()
                ->_doc($mapping);
        }

        $this->indices
            ->create($payload->toArray());
    }

    /**
     * @inheritdoc
     */
    public function delete(Index $index): void
    {
        $payload = (new Payload())
            ->index($index->getName());

        $this->indices
            ->delete($payload->toArray());
    }

    /**
     * @inheritdoc
     */
    public function updateSettings(Index $index, bool $force = false): void
    {
        $settings = $index->getSettings();

        if ($settings->count() == 0) {
            throw new \UnexpectedValueException(sprintf(
                '%s settings payload is empty',
                $index->getName()
            ));
        }

        $basePayload = (new Payload())
            ->index($index->getName());

        // @formatter:off
        $settingsPayload = (clone $basePayload)
            ->body()
                ->settings($settings)
            ->end();
        // @formatter:on

        if ($force) {
            $this->indices
                ->close($basePayload->toArray());
        }

        $this->indices
            ->putSettings($settingsPayload->toArray());

        if ($force) {
            $this->indices
                ->open($basePayload->toArray());
        }
    }

    /**
     * @inheritdoc
     */
    public function updateMapping(Index $index): void
    {
        $mapping = $index->getMapping();

        if ($mapping->count() == 0) {
            throw new \UnexpectedValueException(sprintf(
                '%s mapping payload is empty',
                $index->getName()
            ));
        }

        // @formatter:off
        $payload = (new Payload())
            ->index($index->getName())
            ->type('_doc')
            ->body()
                ->_doc($mapping)
            ->end();
        // @formatter:on

        $this->indices
            ->putMapping($payload->toArray());
    }
}
