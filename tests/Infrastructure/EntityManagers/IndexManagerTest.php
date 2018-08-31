<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers\IndexManager;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;

class IndexManagerTest extends AppTestCase
{
    /**
     * @var IndexStub
     */
    private $index;

    /**
     * @var IndexManager
     */
    private $indexManager;

    public function testExistsMethod(): void
    {
        $payload = (new Payload())
            ->index($this->index->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->assertTrue($this->indexManager->exists($this->index));
    }

    public function testCreateMethod(): void
    {
        $this->indexManager
            ->create($this->index);

        $payload = (new Payload())
            ->index($this->index->getName());

        $this->assertTrue($this->client->indices()->exists($payload->toArray()));
    }

    public function testDeleteMethod(): void
    {
        $payload = (new Payload())
            ->index($this->index->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->indexManager
            ->delete($this->index);

        $this->assertFalse($this->client->indices()->exists($payload->toArray()));
    }

    public function testPutSettingsMethodWithoutForce(): void
    {
        $this->expectExceptionMessageRegExp('/.*?Can\'t update non dynamic settings.*?/');

        $payload = (new Payload())
            ->index($this->index->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->indexManager
            ->putSettings($this->index);

        $this->addToAssertionCount(1);
    }

    public function testPutSettingsMethodWithForce(): void
    {
        $payload = (new Payload())
            ->index($this->index->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->indexManager
            ->putSettings($this->index, true);

        $settings = $this->client->indices()
            ->getSettings($payload->toArray());

        $this->assertArraySubset(
            $this->index->getSettings()->toArray(),
            array_get($settings, $this->index->getName() . '.settings.index')
        );
    }

    public function testGetSettingsMethod(): void
    {
        // @formatter:off
        $payload = (new Payload())
            ->index($this->index->getName())
            ->body()
                ->settings($this->index->getSettings())
            ->end();
        // @formatter:on

        $this->client->indices()
            ->create($payload->toArray());

        $this->assertArraySubset(
            $this->index->getSettings()->toArray(),
            $this->indexManager->getSettings($this->index)->toArray()
        );
    }

    public function testPutMappingMethod(): void
    {
        $basePayload = (new Payload())
            ->index($this->index->getName());

        // @formatter:off
        $createPayload = (clone $basePayload)
            ->body()
                ->settings($this->index->getSettings())
            ->end();
        // @formatter:on

        $this->client->indices()
            ->create($createPayload->toArray());

        $this->indexManager
            ->putMapping($this->index);

        $mapping = $this->client->indices()
            ->getMapping($basePayload->toArray());

        $this->assertSame(
            $this->index->getMapping()->toArray(),
            array_get($mapping, $this->index->getName() . '.mappings._doc')
        );
    }

    public function testGetMappingMethod(): void
    {
        // @formatter:off
        $payload = (new Payload())
            ->index($this->index->getName())
            ->body()
                ->settings($this->index->getSettings())
                ->mappings()
                    ->_doc($this->index->getMapping())
                ->end()
            ->end();
        // @formatter:on

        $this->client->indices()
            ->create($payload->toArray());

        $this->assertSame(
            $this->index->getMapping()->toArray(),
            $this->indexManager->getMapping($this->index)->toArray()
        );
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        // @formatter:off
        $mapping = (new Payload())
            ->properties()
                ->content()
                    ->type('text')
                    ->analyzer('content')
                ->end()
            ->end();
        // @formatter:on

        // @formatter:off
        $settings = (new Payload())
            ->analysis()
                ->analyzer()
                    ->content()
                        ->type('custom')
                        ->tokenizer('whitespace')
                    ->end()
                ->end()
            ->end();
        // @formatter:on

        $this->index = new IndexStub(null, $mapping, $settings);
        $this->indexManager = new IndexManager($this->client);
    }
}
