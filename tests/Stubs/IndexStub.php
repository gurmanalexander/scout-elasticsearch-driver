<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;

class IndexStub extends Index
{
    /**
     * @param Payload|null $mapping
     * @param Payload|null $settings
     */
    public function __construct(Payload $mapping = null, Payload $settings = null)
    {
        parent::__construct('test', $mapping, $settings);
    }
}
