<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Entities;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;

class Index
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Payload
     */
    private $mapping;

    /**
     * @var Payload
     */
    private $settings;

    /**
     * @param string $name
     * @param Payload|null $mapping
     * @param Payload|null $settings
     */
    public function __construct(
        string $name,
        Payload $mapping = null,
        Payload $settings = null
    ) {
        $this->name = config('scout.prefix') . $name;
        $this->mapping = $mapping ?? new Payload();
        $this->settings = $settings ?? new Payload();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Payload
     */
    public function getMapping(): Payload
    {
        return $this->mapping;
    }

    /**
     * @return Payload
     */
    public function getSettings(): Payload
    {
        return $this->settings;
    }
}
