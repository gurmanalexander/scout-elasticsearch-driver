<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Document;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers\BulkDocumentManager;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\EnvTestCase;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;
use stdClass;

class BulkDocumentManagerTest extends EnvTestCase
{
    /**
     * @var DocumentManagerContract
     */
    private $documentManager;

    public function testIndexMethodWithForce(): void
    {
        $documents = collect([
            new Document(1, (new Payload())->content('foo')),
            new Document(2, (new Payload())->content('bar'))
        ]);

        $this->documentManager
            ->index(new IndexStub(), $documents, true);

        $this->assertEquals([1, 2], $this->getIndexStubDocumentIds());
    }

    public function testDeleteMethodWithForce(): void
    {
        // @formatter:off
        $payload = (new Payload())
            ->index((new IndexStub())->getName())
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
            ->delete(new IndexStub(), $documents, true);

        $this->assertEquals([2], $this->getIndexStubDocumentIds());
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->documentManager = new BulkDocumentManager($this->client);

        $payload = (new Payload())
            ->index((new IndexStub())->getName());

        $this->client->indices()
            ->create($payload->toArray());
    }

    /**
     * @return array
     */
    private function getIndexStubDocumentIds(): array
    {
        // @formatter:off
        $payload = (new Payload())
            ->index((new IndexStub())->getName())
            ->type('_doc')
            ->body()
                ->query()
                    ->matchAll(new stdClass())
                ->end()
            ->end();
        // @formatter:on

        $result = $this->client
            ->search($payload->toArray());

        $ids = array_pluck($result['hits']['hits'], '_id');
        sort($ids, SORT_NUMERIC);

        return $ids;
    }
}
