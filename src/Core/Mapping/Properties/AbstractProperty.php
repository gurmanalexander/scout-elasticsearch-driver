<?php
declare(strict_types=1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Mapping\Properties;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Mapping\Properties\Property;

abstract class AbstractProperty implements Property
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
     * @inheritdoc
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
