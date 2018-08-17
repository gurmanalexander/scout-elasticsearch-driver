<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers\IndexManager;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\EnvTestCase;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;

class IndexManagerTest extends EnvTestCase
{
    /**
     * @var IndexStub
     */
    private $indexStub;

    /**
     * @var IndexManager
     */
    private $indexManager;

    public function testExistsMethod(): void
    {
        $payload = (new Payload())
            ->index($this->indexStub->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->assertTrue($this->indexManager->exists($this->indexStub));
    }

    public function testCreateMethod(): void
    {
        $this->indexManager
            ->create($this->indexStub);

        $payload = (new Payload())
            ->index($this->indexStub->getName());

        $this->assertTrue($this->client->indices()->exists($payload->toArray()));
    }

    public function testDeleteMethod(): void
    {
        $payload = (new Payload())
            ->index($this->indexStub->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->indexManager
            ->delete($this->indexStub);

        $this->assertFalse($this->client->indices()->exists($payload->toArray()));
    }

    public function testUpdateSettingsMethodWithoutForce(): void
    {
        $this->expectExceptionMessageRegExp('/.*?Can\'t update non dynamic settings.*?/');

        $payload = (new Payload())
            ->index($this->indexStub->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->indexManager
            ->updateSettings($this->indexStub);

        $this->addToAssertionCount(1);
    }

    public function testUpdateSettingsMethodWithForce(): void
    {
        $payload = (new Payload())
            ->index($this->indexStub->getName());

        $this->client->indices()
            ->create($payload->toArray());

        $this->indexManager
            ->updateSettings($this->indexStub, true);

        $this->addToAssertionCount(1);
    }

    public function testUpdateMappingMethod(): void
    {
        // @formatted:off
        $payload = (new Payload())
            ->index($this->indexStub->getName())
            ->body()
                ->settings($this->indexStub->getSettings())
            ->end();
        // @formatted:on

        $this->client->indices()
            ->create($payload->toArray());

        $this->indexManager
            ->updateMapping($this->indexStub);

        $this->addToAssertionCount(1);
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

        $this->indexStub = new IndexStub($mapping, $settings);
        $this->indexManager = new IndexManager($this->client);
    }
}
