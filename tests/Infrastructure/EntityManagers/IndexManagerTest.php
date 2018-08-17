<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Namespaces\IndicesNamespace as IndicesNamespaceContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers\IndexManager;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;

class IndexManagerTest extends AppTestCase
{
    /**
     * @var IndicesNamespaceContract
     */
    private $indices;

    /**
     * @var Index
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

        $this->indices
            ->create($payload->toArray());

        $this->assertTrue($this->indexManager->exists($this->index));
    }

    public function testCreateMethod(): void
    {
        $this->indexManager
            ->create($this->index);

        $payload = (new Payload())
            ->index($this->index->getName());

        $this->assertTrue($this->indices->exists($payload->toArray()));
    }

    public function testDeleteMethod(): void
    {
        $payload = (new Payload())
            ->index($this->index->getName());

        $this->indices
            ->create($payload->toArray());

        $this->indexManager
            ->delete($this->index);

        $this->assertFalse($this->indices->exists($payload->toArray()));
    }

    public function testUpdateSettingsMethodWithoutForcing(): void
    {
        $this->expectExceptionMessageRegExp('/.*?Can\'t update non dynamic settings.*?/');

        $payload = (new Payload())
            ->index($this->index->getName());

        $this->indices
            ->create($payload->toArray());

        $this->indexManager
            ->updateSettings($this->index);

        $this->addToAssertionCount(1);
    }

    public function testUpdateSettingsMethodWithForcing(): void
    {
        $payload = (new Payload())
            ->index($this->index->getName());

        $this->indices
            ->create($payload->toArray());

        $this->indexManager
            ->updateSettings($this->index, true);

        $this->addToAssertionCount(1);
    }

    public function testUpdateMappingMethod(): void
    {
        // @formatted:off
        $payload = (new Payload())
            ->index($this->index->getName())
            ->body()
                ->settings($this->index->getSettings())
            ->end();
        // @formatted:on

        $this->indices
            ->create($payload->toArray());

        $this->indexManager
            ->updateMapping($this->index);

        $this->addToAssertionCount(1);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        /** @var ClientContract $client */
        $client = resolve(ClientContract::class);

        $this->indices = $client->indices();
        $this->indexManager = new IndexManager($client);

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

        $this->index = new Index('test', $mapping, $settings);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        parent::tearDown();

        $payload = (new Payload())
            ->index($this->index->getName())
            ->toArray();

        if ($this->indices->exists($payload)) {
            $this->indices->delete($payload);
        }
    }
}
