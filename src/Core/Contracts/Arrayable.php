<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts;

interface Arrayable
{
    /**
     * @return array
     */
    public function toArray(): array;
}
