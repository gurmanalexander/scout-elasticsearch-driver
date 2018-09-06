<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core;

use PHPUnit\Framework\TestCase;

class EngineTest extends TestCase
{
    /**
     * @var IndexStub
     */
    private $firstIndex;

    /**
     * @var IndexStub
     */
    private $secondIndex;

    /**
     * @var IndexManagerContract
     */
    private $indexManager;

    /**
     * @var DocumentManagerContract
     */
    private $documentManager;

    /**
     * @var Engine
     */
    private $engine;

    public function testUpdateMethod(): void
    {
        /*$models = new EloquentCollection([
            new ModelStub(['id' => 1, 'name' => 'first'], $this->firstIndex),
            new ModelStub(['id' => 2, 'name' => 'second'], $this->firstIndex),
            new ModelStub(['id' => 3, 'name' => 'third'], $this->secondIndex),
        ]);

        $this->engine
            ->update($models);

        $firstIndexDocuments = $this->getDocuments($this->firstIndex);
        $this->assertCount(2, $firstIndexDocuments);
        $this->assertEquals(1, $firstIndexDocuments->get(0)->getId());
        $this->assertEquals(['id' => 1, 'name' => 'first'], $firstIndexDocuments->get(0)->getFields()->toArray());
        $this->assertEquals(2, $firstIndexDocuments->get(1)->getId());
        $this->assertEquals(['id' => 2, 'name' => 'second'], $firstIndexDocuments->get(1)->getFields()->toArray());

        $secondIndexDocuments = $this->getDocuments($this->secondIndex);
        $this->assertCount(1, $secondIndexDocuments);
        $this->assertEquals(3, $secondIndexDocuments->get(0)->getId());
        $this->assertEquals(['id' => 3, 'name' => 'third'], $secondIndexDocuments->get(0)->getFields()->toArray());*/
    }

    public function testDeleteMethod(): void
    {
        /*$firstIndexDocuments = new BaseCollection([
            new Document(1, (new Payload())->id(1)->name('first')),
            new Document(2, (new Payload())->id(2)->name('second')),
        ]);

        $secondIndexDocuments = new BaseCollection([
            new Document(3, (new Payload())->id(3)->name('third')),
        ]);

        $this->documentManager
            ->index($this->firstIndex, $firstIndexDocuments, true)
            ->index($this->secondIndex, $secondIndexDocuments, true);

        $models = new EloquentCollection([
            new ModelStub(['id' => 1, 'name' => 'first'], $this->firstIndex),
            new ModelStub(['id' => 3, 'name' => 'third'], $this->secondIndex),
        ]);

        $this->engine
            ->delete($models);

        $firstIndexDocuments = $this->getDocuments($this->firstIndex);
        $this->assertCount(1, $firstIndexDocuments);
        $this->assertEquals(2, $firstIndexDocuments->get(0)->getId());
        $this->assertEquals(['id' => 2, 'name' => 'second'], $firstIndexDocuments->get(0)->getFields()->toArray());

        $secondIndexDocuments = $this->getDocuments($this->secondIndex);
        $this->assertCount(0, $secondIndexDocuments);*/
    }

    protected function setUp()
    {
        /*parent::setUp();

        $this->firstIndex = new IndexStub('first');
        $this->secondIndex = new IndexStub('second');

        $this->indexManager = resolve(IndexManagerContract::class);
        $this->documentManager = resolve(DocumentManagerContract::class);
        $this->engine = resolve(EngineManager::class)->engine('elastic');

        $this->indexManager
            ->create($this->firstIndex)
            ->create($this->secondIndex);*/
    }

    /**
     * @param Index $index
     * @return BaseCollection
     */
    private function getDocuments(Index $index): BaseCollection
    {
        // @formatter:off
        /*$payload = (new Payload())
            ->query()
                ->matchAll(new stdClass())
            ->end()
            ->{'\sort'}()
                ->push()
                    ->_id('asc')
                ->end()
            ->end();*/
        // @formatter:on

        /*return $this->documentManager
            ->search($index, $payload);*/
    }
}
