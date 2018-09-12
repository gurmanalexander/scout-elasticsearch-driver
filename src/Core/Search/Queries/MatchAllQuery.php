<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Search\Queries;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Search\Queries\Query;
use stdClass;

final class MatchAllQuery implements Query
{
    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'match_all' => new stdClass()
        ];
    }
}
