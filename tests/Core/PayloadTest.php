<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;

class PayloadTest extends AppTestCase
{
    public function testKeyTransformation(): void
    {
        $payload = (new Payload())
            ->fooBar(1)
            ->_fooBar_(2)
            ->_foo_Bar(3)
            ->_Foo_Bar(4)
            ->foo_bar(5)
            ->foo_bar_(6);

        $this->assertSame(
            [
                'foo_bar' => 5,
                '_foo_bar_' => 2,
                '_foo_bar' => 4,
                'foo_bar_' => 6,
            ],
            $payload->toArray()
        );
    }
}
