<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Entities;

use Illuminate\Support\Collection as BaseCollection;

final class Document
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var BaseCollection
     */
    private $content;

    /**
     * @param string $id
     * @param BaseCollection $content
     */
    public function __construct(string $id, BaseCollection $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return BaseCollection
     */
    public function getContent(): BaseCollection
    {
        return $this->content;
    }
}
