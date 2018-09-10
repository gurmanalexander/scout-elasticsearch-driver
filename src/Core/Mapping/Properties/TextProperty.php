<?php
declare(strict_types=1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core\Mapping\Properties;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Settings\Analyzers\Analyzer;

class TextProperty extends AbstractProperty
{
    /**
     * @var Analyzer
     */
    private $analyzer;

    /**
     * @param string $name
     * @param Analyzer $analyzer
     */
    public function __construct(string $name, ?Analyzer $analyzer = null)
    {
        parent::__construct($name);
        $this->analyzer = $analyzer;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $property = [
            'type' => 'text',
        ];

        if (isset($this->analyzer)) {
            $property['analyzer'] = $this->analyzer->getName();
        }

        return $property;
    }
}
