<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Document;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as BaseCollection;
use Laravel\Scout\Searchable as ScoutSearchable;

trait Searchable
{
    use ScoutSearchable {
        ScoutSearchable::registerSearchableMacros as registerScoutSearchableMacros;
    }

    /**
     * @return Index
     */
    abstract public function getSearchableIndex(): Index;

    /**
     * @return Document
     */
    public function toSearchableDocument(): Document
    {
        $id = $this->getKey();
        $fields = Payload::fromArray($this->toSearchableArray());

        return new Document($id, $fields);
    }

    public function registerSearchableMacros(): void
    {
        $this->registerScoutSearchableMacros();
        $this->registerGetSearchableIndicesMacro();
        $this->registerToSearchableDocumentsMacro();

    }

    private function registerGetSearchableIndicesMacro(): void
    {
        BaseCollection::macro('getSearchableIndices', function () {
            $indices = collect();

            $this->each(function (Model $model) use ($indices) {
                $index = $model->getSearchableIndex();
                $indexName = $index->getName();

                $indices->put($indexName, $index);
            });

            return $indices;
        });
    }

    private function registerToSearchableDocumentsMacro(): void
    {
        BaseCollection::macro('toSearchableDocuments', function () {
            $documents = collect();

            $this->each(function (Model $model) use ($documents) {
                $document = $model->toSearchableDocument();
                $indexName = $model->getSearchableIndex()->getName();

                if (!$documents->has($indexName)) {
                    $documents->put($indexName, collect());
                }

                $documents->get($indexName)->push($document);
            });

            return $documents;
        });
    }
}
