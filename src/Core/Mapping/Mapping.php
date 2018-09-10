<?php
declare(strict_types=1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Mapping;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Arrayable;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Mapping\Properties\Property;
use Illuminate\Support\Collection as BaseCollection;

class Mapping implements Arrayable
{
    /**
     * @var BaseCollection
     */
    private $properties;

    public function __construct()
    {
        $this->properties = collect();
    }

    /**
     * @param Property $property
     * @return self
     */
    public function addProperty(Property $property): self
    {
        $this->properties->push($property);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $mapping = [];

        $properties = $this->properties->mapWithKeys(function (Property $property) {
            return [
                $property->getName() => $property->toArray()
            ];
        });

        if ($properties->count() > 0) {
            $mapping['properties'] = $properties->all();
        }

        return $mapping;
    }
}
