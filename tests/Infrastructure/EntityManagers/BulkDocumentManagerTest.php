<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Document;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Dependencies\Client;
use PHPUnit\Framework\TestCase;

class BulkDocumentManagerTest extends TestCase
{
    use Client;

    /**
     * @var Index
     */
    private $index;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    public function test_documents_can_be_indexed_with_force(): void
    {
        $documents = collect([
            new Document('1', collect(['name' => 'foo'])),
            new Document('2', collect(['name' => 'bar']))
        ]);

        $this->documentManager
            ->index($this->index, $documents, true);

        $this->assertSame(
            [
                '1' => [
                    'name' => 'foo'
                ],
                '2' => [
                    'name' => 'bar'
                ]
            ],
            $this->getIndexDocuments($this->index->getName())
        );
    }

    public function test_documents_can_be_deleted_with_force(): void
    {
        $this->createIndexDocuments($this->index->getName(), [
            '1' => [
                'name' => 'foo'
            ],
            '2' => [
                'name' => 'bar'
            ]
        ]);

        $documents = collect([
            new Document('1', collect(['name' => 'foo']))
        ]);

        $this->documentManager
            ->delete($this->index, $documents, true);

        $this->assertSame(
            [
                '2' => [
                    'name' => 'bar'
                ]
            ],
            $this->getIndexDocuments($this->index->getName())
        );
    }

    public function test_match_all_query_can_return_all_documents(): void
    {
        // @formatter:off
        /*$createPayload = (new Payload())
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
            ->end();*/
        // @formatter:on

        /*$this->client
            ->bulk($createPayload->toArray());

        $documents = $this->documentManager
            ->search($this->index, $searchPayload);

        $this->assertCount(2, $documents);
        $this->assertEquals(1, $documents->get(0)->getId());
        $this->assertEquals(['content' => 'foo'], $documents->get(0)->getFields()->toArray());
        $this->assertEquals(2, $documents->get(1)->getId());
        $this->assertEquals(['content' => 'bar'], $documents->get(1)->getFields()->toArray());*/

        // todo
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->index = new Index('test');
        $this->documentManager = new BulkDocumentManager($this->client);

        $this->createIndex($this->index->getName());
    }
}
