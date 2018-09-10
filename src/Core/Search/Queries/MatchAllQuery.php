<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Search\Queries;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Search\Queries\Query;

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
