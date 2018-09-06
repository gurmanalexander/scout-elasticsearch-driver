<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Core\Queries;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Core\Contracts\Queries\Query;

final class MatchAllQuery implements Query
{
    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}
