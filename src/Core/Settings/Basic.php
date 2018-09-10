<?php
declare(strict_types=1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Settings;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Arrayable;

class Basic implements Arrayable
{
    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [];
    }
}
