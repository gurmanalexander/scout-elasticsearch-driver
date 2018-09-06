<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Entities;

use BabenkoIvan\ScoutElasticsearchDriver\Dependencies\App;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    use App;

    /**
     * @return array
     */
    public function prefixProvider(): array
    {
        return [
            [''],
            ['foo_']
        ];
    }

    /**
     * @dataProvider prefixProvider
     * @testdox configured prefix "$prefix" can be added to index name
     *
     * @param string $prefix
     */
    public function test_configured_prefix_can_be_added_to_index_name(string $prefix): void
    {
        $name = 'test';
        config(['scout.prefix' => $prefix]);
        $this->assertSame($prefix . $name, (new Index($name))->getName());
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        config(['scout.prefix' => '']);
    }
}
