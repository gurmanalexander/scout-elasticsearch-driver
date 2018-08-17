<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\Client;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Namespaces\IndicesNamespace as IndicesNamespaceContract;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\Client\Namespaces\IndicesNamespace;
use Elasticsearch\Client as AdapteeClient;

class Client implements ClientContract
{
    /**
     * @var AdapteeClient
     */
    private $adapteeClient;

    /**
     * @var IndicesNamespaceContract
     */
    private $indicesNamespace;

    /**
     * @param AdapteeClient $adapteeClient
     */
    public function __construct(AdapteeClient $adapteeClient)
    {
        $this->adapteeClient = $adapteeClient;
        $this->indicesNamespace = new IndicesNamespace($adapteeClient->indices());
    }

    /**
     * @inheritdoc
     */
    public function bulk(array $params): array
    {
        return $this->adapteeClient
            ->bulk($params);
    }

    /**
     * @inheritdoc
     */
    public function search(array $params): array
    {
        return $this->adapteeClient
            ->search($params);
    }

    /**
     * @inheritdoc
     */
    public function indices(): IndicesNamespaceContract
    {
        return $this->indicesNamespace;
    }
}
