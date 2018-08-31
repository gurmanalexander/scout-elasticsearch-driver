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
        $secondDocument = new Document(2, (new Payload())->content('second'));

        $documents = collect([$firstDocument, $secondDocument]);

        $this->documentManager
            ->index($this->index, $documents, true);

        $this->assertEquals(
            [
                1 => [
                    'content' => 'first'
                ],
                2 => [
                    'content' => 'second'
                ]
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
                2 => [
                    'content' => 'bar'
                ]
            ],
            $this->getDocuments()->toArray()
        );
    }

    public function testSearchMethod(): void
    {
        // @formatter:off
        $createPayload = (new Payload())
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

        $searchPayload = (new Payload())
            ->query()
                ->matchAll(new stdClass())
            ->end()
            ->{'\sort'}()
                ->push()
                    ->_id('asc')
                ->end()
            ->end();
        // @formatter:on

        $this->client
            ->bulk($createPayload->toArray());

        $documents = $this->documentManager
            ->search($this->index, $searchPayload);

        $this->assertCount(2, $documents);
        $this->assertEquals(1, $documents->get(0)->getId());
        $this->assertEquals(['content' => 'foo'], $documents->get(0)->getFields()->toArray());
        $this->assertEquals(2, $documents->get(1)->getId());
        $this->assertEquals(['content' => 'bar'], $documents->get(1)->getFields()->toArray());
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
                ->{'\sort'}()
                    ->push()
                        ->_id('asc')
                    ->end()
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
