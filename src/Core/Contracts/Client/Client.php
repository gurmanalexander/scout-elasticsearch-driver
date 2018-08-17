<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Namespaces\IndicesNamespace;

interface Client
{
    /**
     * @return IndicesNamespace
     */
    public function indices(): IndicesNamespace;
}
