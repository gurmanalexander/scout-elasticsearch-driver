<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Document;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers\BulkDocumentManager;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;
use Illuminate\Support\Collection;
use stdClass;

class BulkDocumentManagerTest extends AppTestCase
{
    /**
     * @var IndexStub
     */
    private $index;

    /**
     * @var DocumentManagerContract
     */
    private $documentManager;

    public function testIndexMethodWithForce(): void
    {
        $firstDocument = new Document(1, (new Payload())->content('first'));
        $secondDocument = new Document(1, (new Payload())->content('second'));

        $documents = collect([$firstDocument, $secondDocument]);

        $this->documentManager
            ->index($this->index, $documents, true);

        $this->assertEquals(
            [
                $firstDocument->getId() => $firstDocument->getFields()->toArray(),
                $secondDocument->getId() => $secondDocument->getFields()->toArray()
            ],
            $this->getDocuments()->toArray()
        );
    }

    public function testDeleteMethodWithForce(): void
    {
        // @formatter:off
        $payload = (new Payload())
            ->index($this->index->getName())
            ->type('_doc')
            ->refresh('true')
            ->body()
                ->push()
                    ->index()
                        ->_id(1)
                    ->end()
                ->end()
                ->push()
                    ->content('foo')
                ->end()
                ->push()
                    ->index()
                        ->_id(2)
                    ->end()
                ->end()
                ->push()
                    ->content('bar')
                ->end()
            ->end();
        // @formatter:on

        $this->client
            ->bulk($payload->toArray());

        $documents = collect([
            new Document(1, (new Payload())->content('foo'))
        ]);

        $this->documentManager
            ->delete($this->index, $documents, true);

        $this->assertEquals(
            [
                $payload['body'][2]['index']['_id'] => $payload['body'][3]->toArray()
            ],
            $this->getDocuments()->toArray()
        );
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->index = new IndexStub();
        $this->documentManager = new BulkDocumentManager($this->client);

        $payload = (new Payload())
            ->index($this->index->getName());

        $this->client->indices()
            ->create($payload->toArray());
    }

    /**
     * @return Collection
     */
    private function getDocuments(): Collection
    {
        // @formatter:off
        $payload = (new Payload())
            ->index($this->index->getName())
            ->type('_doc')
            ->body()
                ->query()
                    ->matchAll(new stdClass())
                ->end()
            ->end();
        // @formatter:on

        $result = $this->client
            ->search($payload->toArray());

        return collect($result['hits']['hits'])->mapWithKeys(function (array $hit) {
            return [$hit['_id'] => $hit['_source']];
        });
    }
}
