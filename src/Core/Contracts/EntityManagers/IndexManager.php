<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;

interface IndexManager
{
    /**
     * @param Index $index
     * @return bool
     */
    public function exists(Index $index): bool;

    /**
     * @param Index $index
     */
    public function create(Index $index): void;

    /**
     * @param Index $index
     */
    public function delete(Index $index): void;

    /**
     * @param Index $index
     * @param bool $force The force flag will cause index closing and reopening after update.
     */
    public function updateSettings(Index $index, bool $force = false): void;

    /**
     * @param Index $index
     */
    public function updateMapping(Index $index): void;
}
