<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Search\Request;
use Illuminate\Support\Collection as BaseCollection;

interface DocumentManager
{
    const DEFAULT_TYPE = '_doc';

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
     * @param Request $request
     * @return BaseCollection
     */
    public function search(Index $index, Request $request): BaseCollection;
}
