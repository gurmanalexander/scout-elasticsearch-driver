<?php
declare(strict_types=1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Settings\Analyzers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Settings\Analyzers\Analyzer;

abstract class AbstractAnalyzer implements Analyzer
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    abstract public function toArray(): array;
}
