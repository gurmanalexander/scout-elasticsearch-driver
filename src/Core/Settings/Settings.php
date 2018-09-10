<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Settings;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Arrayable;

class Settings implements Arrayable
{
    /**
     * @var Basic
     */
    private $basic;

    /**
     * @var Analysis
     */
    private $analysis;

    /**
     * @param Basic $basic
     * @param Analysis $analysis
     */
    public function __construct(
        Basic $basic,
        Analysis $analysis
    ) {
        $this->basic = $basic;
        $this->analysis = $analysis;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $settings = $this->basic->toArray();
        $settings['analysis'] = $this->analysis->toArray();
        return $settings;
    }
}
