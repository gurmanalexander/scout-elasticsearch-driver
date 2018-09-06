<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Dependencies;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager;
use stdClass;

trait Client
{
    use App;

    /**
     * @var ClientContract
     */
    protected $client;

    /**
     * @before
     */
    public function makeClient(): void
    {
        $this->client = resolve(ClientContract::class);
    }

    /**
     * @after
     */
    public function dropIndices(): void
    {
        $this->deleteIndex('*');
    }

    /**
     * @param string $name
     * @param array $mapping
     * @param array $settings
     */
    protected function createIndex(string $name, array $mapping = [], array $settings = []): void
    {
        $payload = [
            'index' => $name
        ];

        if (!empty($settings) || !empty($mapping)) {
            $payload['body'] = [];
        }

        if (!empty($settings)) {
            $payload['body']['settings'] = $settings;
        }

        if (!empty($mapping)) {
            $payload['body']['mappings'] = [
                DocumentManager::DEFAULT_TYPE => $mapping
            ];
        }

        $this->client->indices()
            ->create($payload);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isIndexExists(string $name): bool
    {
        $payload = [
            'index' => $name
        ];

        return $this->client->indices()
            ->exists($payload);
    }

    /**
     * @param string $name
     */
    protected function deleteIndex(string $name): void
    {
        $payload = [
            'index' => $name
        ];

        $this->client->indices()
            ->delete($payload);
    }

    /**
     * @param string $name
     * @return array
     */
    protected function getIndexSettings(string $name): array
    {
        $payload = [
            'index' => $name
        ];

        $settings = $this->client->indices()
            ->getSettings($payload);

        return array_get($settings, $name . '.settings.index');
    }

    /**
     * @param string $name
     * @return array
     */
    protected function getIndexMapping(string $name): array
    {
        $payload = [
            'index' => $name
        ];

        $mapping = $this->client->indices()
            ->getMapping($payload);

        return array_get($mapping, $name . '.mappings._doc');
    }

    /**
     * @param string $name
     * @param array $documents ['1' => ['name' => 'foo'], '2' => ['name' => 'bar']]
     */
    protected function createIndexDocuments(string $name, array $documents): void
    {
        $payload = [
            'index' => $name,
            'type' => DocumentManager::DEFAULT_TYPE,
            'refresh' => 'true',
            'body' => []
        ];

        collect($documents)->each(function (array $content, string $id) use (&$payload) {
            $payload['body'][] = [
                'index' => [
                    '_id' => $id
                ]
            ];

            $payload['body'][] = $content;
        });

        $this->client
            ->bulk($payload);
    }

    /**
     * @param string $name
     * @return array ['1' => ['name' => 'foo'], '2' => ['name' => 'bar']]
     */
    protected function getIndexDocuments(string $name): array
    {
        $payload = [
            'index' => $name,
            'type' => DocumentManager::DEFAULT_TYPE,
            'body' => [
                'query' => [
                    'match_all' => new stdClass()
                ],
                'sort' => [
                    [
                        '_id' => 'asc'
                    ]
                ]
            ]
        ];

        $result = $this->client
            ->search($payload);

        return collect($result['hits']['hits'])->mapWithKeys(function (array $hit) {
            return [
                $hit['_id'] => $hit['_source']
            ];
        })->all();
    }
}
