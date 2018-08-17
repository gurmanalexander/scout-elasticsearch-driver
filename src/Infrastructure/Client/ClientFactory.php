<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\Client;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\ClientFactory as ClientFactoryContract;
use Elasticsearch\ClientBuilder;

class ClientFactory implements ClientFactoryContract
{
    /**
     * @inheritdoc
     */
    public static function fromConfig(array $config): ClientContract
    {
        $adapteeClient = ClientBuilder::fromConfig($config);
        return new Client($adapteeClient);
    }
}
