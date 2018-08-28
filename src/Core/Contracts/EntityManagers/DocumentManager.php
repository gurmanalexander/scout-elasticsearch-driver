<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use Illuminate\Support\Collection;

interface DocumentManager
{
    /**
     * @param Index $index
     * @param Collection $collection
     * @param bool $force Force immediate indexing
     * @return self
     */
    public function index(Index $index, Collection $collection, bool $force = false): self;

    /**
     * @param Index $index
     * @param Collection $collection
     * @param bool $force Force immediate deletion
     * @return self
     */
    public function delete(Index $index, Collection $collection, bool $force = false): self;
}
