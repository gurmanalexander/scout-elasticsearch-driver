<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Entities;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;

class Document
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @var Payload
     */
    private $fields;

    /**
     * @param mixed $id
     * @param Payload $fields
     */
    public function __construct($id, Payload $fields)
    {
        $this->id = $id;
        $this->fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Payload
     */
    public function getFields(): Payload
    {
        return $this->fields;
    }
}
