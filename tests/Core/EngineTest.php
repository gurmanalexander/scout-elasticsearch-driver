<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\IndexManager as IndexManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;

class EngineTest extends AppTestCase
{
    /**
     * @var IndexStub
     */
    private $newIndex;

    /**
     * @var IndexStub
     */
    private $oldIndex;

    /**
     * @var IndexManagerContract
     */
    private $indexManager;

    /**
     * @var DocumentManagerContract
     */
    private $documentManager;

    public function testUpdateMethod(): void
    {
        // todo
    }

    public function testDeleteMethod(): void
    {
        // todo
    }

    protected function setUp()
    {
        parent::setUp();

        $this->newIndex = new IndexStub('new');
        $this->oldIndex = new IndexStub('old');

        $this->indexManager = resolve(IndexManagerContract::class);
        $this->documentManager = resolve(DocumentManagerContract::class);

        $this->indexManager
            ->create($this->newIndex)
            ->create($this->oldIndex);
    }
}
