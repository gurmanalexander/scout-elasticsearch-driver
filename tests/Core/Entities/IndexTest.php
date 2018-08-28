<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Core\Entities;

use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;

class IndexTest extends AppTestCase
{
    public function testNamePrefix(): void
    {
        config(['scout.prefix' => '']);
        $this->assertSame('test', (new IndexStub())->getName());

        config(['scout.prefix' => 'foo_']);
        $this->assertSame('foo_test', (new IndexStub())->getName());
    }
}
