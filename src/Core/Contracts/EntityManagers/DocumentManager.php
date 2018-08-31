<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use Illuminate\Support\Collection as BaseCollection;

interface DocumentManager
{
    /**
     * @param Index $index
     * @param BaseCollection $collection
     * @param bool $force Force immediate indexing
     * @return self
     */
    public function index(Index $index, BaseCollection $collection, bool $force = false): self;

    /**
     * @param Index $index
     * @param BaseCollection $collection
     * @param bool $force Force immediate deletion
     * @return self
     */
    public function delete(Index $index, BaseCollection $collection, bool $force = false): self;

    /**
     * @param Index $index
     * @param Payload $payload
     * @return BaseCollection
     */
    public function search(Index $index, Payload $payload): BaseCollection;
}
