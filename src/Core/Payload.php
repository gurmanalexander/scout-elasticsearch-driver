<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core;

use BabenkoIvan\FluentArray\FluentArray;
use BabenkoIvan\FluentArray\NamingStrategies\UnderscoreStrategy;

class Payload extends FluentArray
{
    /**
     * @inheritdoc
     */
    protected function transformKey(string $key): string
    {
        return (new UnderscoreStrategy())->transform($key);
    }

    /**
     * @inheritdoc
     */
    protected static function defaultConfig(): FluentArray
    {
        return new static();
    }
}
