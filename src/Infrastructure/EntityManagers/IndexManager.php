<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Namespaces\IndicesNamespace;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\IndexManager as IndexManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use UnexpectedValueException;

class IndexManager implements IndexManagerContract
{
    /**
     * @var IndicesNamespace
     */
    private $indices;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->indices = $client->indices();
    }

    /**
     * @inheritdoc
     */
    public function exists(Index $index): bool
    {
        $payload = [
            'index' => $index->getName()
        ];

        return $this->indices
            ->exists($payload);
    }

    /**
     * @inheritdoc
     */
    public function create(Index $index): IndexManagerContract
    {
        $settings = $index->getSettings();
        $mapping = $index->getMapping();

        $payload = [
            'index' => $index->getName()
        ];

        if (isset($settings) || isset($mapping)) {
            $payload['body'] = [];
        }

        if (isset($settings)) {
            $payload['body']['settings'] = $settings->toArray();
        }

        if (isset($mapping)) {
            $payload['body']['mappings'] = [
                DocumentManager::DEFAULT_TYPE => $mapping->toArray()
            ];
        }

        $this->indices
            ->create($payload);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function delete(Index $index): IndexManagerContract
    {
        $payload = [
            'index' => $index->getName()
        ];

        $this->indices
            ->delete($payload);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function updateSettings(Index $index, bool $force = false): IndexManagerContract
    {
        $settings = $index->getSettings();

        if (!isset($settings)) {
            throw new UnexpectedValueException(sprintf(
                '%s settings are not specified',
                $index->getName()
            ));
        }

        $basePayload = [
            'index' => $index->getName()
        ];

        $settingsPayload = array_merge($basePayload, [
            'body' => [
                'settings' => $settings->toArray()
            ]
        ]);

        if ($force) {
            $this->indices
                ->close($basePayload);
        }

        $this->indices
            ->putSettings($settingsPayload);

        if ($force) {
            $this->indices
                ->open($basePayload);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function updateMapping(Index $index): IndexManagerContract
    {
        $mapping = $index->getMapping();

        if (!isset($mapping)) {
            throw new UnexpectedValueException(sprintf(
                '%s mapping is not specified',
                $index->getName()
            ));
        }

        $payload = [
            'index' => $index->getName(),
            'type' => DocumentManager::DEFAULT_TYPE,
            'body' => [
                DocumentManager::DEFAULT_TYPE => $mapping->toArray()
            ]
        ];

        $this->indices
            ->putMapping($payload);

        return $this;
    }
}
