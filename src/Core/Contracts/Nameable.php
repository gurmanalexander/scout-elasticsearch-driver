<?php
declare(strict_types=1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts;

interface Nameable
{
    /**
     * @return string
     */
    public function getName(): string;
}
