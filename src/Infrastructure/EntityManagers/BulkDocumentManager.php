<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Document;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use Illuminate\Support\Collection;

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
    public function index(Index $index, Collection $collection, bool $force = false): void
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
    }

    /**
     * @inheritdoc
     */
    public function delete(Index $index, Collection $collection, bool $force = false): void
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
    }
}
