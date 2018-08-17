<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Core\Entities;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;

class IndexTest extends AppTestCase
{
    public function testNamePrefix(): void
    {
        config(['scout.prefix' => '']);
        $this->assertSame('foo', (new Index('foo'))->getName());

        config(['scout.prefix' => 'foo_']);
        $this->assertSame('foo_bar', (new Index('bar'))->getName());
    }
}
