<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Namespaces\IndicesNamespace;

interface Client
{
    /**
     * @param array $params
     * @return array
     */
    public function bulk(array $params): array;

    /**
     * @param array $params
     * @return array
     */
    public function search(array $params): array;

    /**
     * @return IndicesNamespace
     */
    public function indices(): IndicesNamespace;
}
