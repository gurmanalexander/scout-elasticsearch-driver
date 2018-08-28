<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;

class IndexStub extends Index
{
    /**
     * @param string|null $name
     * @param Payload|null $mapping
     * @param Payload|null $settings
     */
    public function __construct(string $name = null, Payload $mapping = null, Payload $settings = null)
    {
        parent::__construct($name ?? 'test', $mapping, $settings);
    }
}
