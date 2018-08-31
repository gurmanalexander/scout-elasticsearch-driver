<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Document;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use Illuminate\Support\Collection as BaseCollection;

class BulkDocumentManager implements DocumentManagerContract
{
    /**
     * @var ClientContract
     */
    private $client;

    public function __construct(ClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function index(Index $index, BaseCollection $collection, bool $force = false): DocumentManagerContract
    {
        $payload = (new Payload())
            ->index($index->getName())
            ->type('_doc')
            ->refreshWhen($force, 'true');

        $collection->each(function (Document $document) use ($payload) {
            // @formatter:off
            $payload->body()
                ->push()
                    ->index()
                        ->_id($document->getId())
                    ->end()
                ->end()
                ->push($document->getFields());
            // @formatter:on
        });

        $this->client
            ->bulk($payload->toArray());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function delete(Index $index, BaseCollection $collection, bool $force = false): DocumentManagerContract
    {
        $payload = (new Payload())
            ->index($index->getName())
            ->type('_doc')
            ->refreshWhen($force, 'true');

        $collection->each(function (Document $document) use ($payload) {
            // @formatter:off
            $payload->body()
                ->push()
                    ->delete()
                        ->_id($document->getId())
                    ->end()
                ->end();
            // @formatter:on
        });

        $this->client
            ->bulk($payload->toArray());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function search(Index $index, Payload $payload): BaseCollection
    {
        $payload = (new Payload())
            ->index($index->getName())
            ->type('_doc')
            ->body($payload);

        $response = $this->client
            ->search($payload->toArray());

        return collect($response['hits']['hits'])->map(function (array $hit) {
            $id = $hit['_id'];
            $fields = Payload::fromArray($hit['_source']);

            return new Document($id, $fields);
        });
    }
}
