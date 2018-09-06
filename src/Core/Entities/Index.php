<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Entities;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Mapping\Mapping;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Settings\Settings;

final class Index
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @param string $name
     * @param Mapping|null $mapping
     * @param Settings|null $settings
     */
    public function __construct(
        string $name,
        Mapping $mapping = null,
        Settings $settings = null
    ) {
        $this->name = config('scout.prefix') . $name;
        $this->mapping = $mapping;
        $this->settings = $settings;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Mapping|null
     */
    public function getMapping(): ?Mapping
    {
        return $this->mapping;
    }

    /**
     * @return Settings|null
     */
    public function getSettings(): ?Settings
    {
        return $this->settings;
    }
}
