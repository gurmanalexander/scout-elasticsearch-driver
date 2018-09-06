<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Search;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Arrayable;

final class Request implements Arrayable
{
    public function __construct()
    {

    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}
