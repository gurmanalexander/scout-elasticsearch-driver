<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Dependencies\Client;
use PHPUnit\Framework\TestCase;

class IndexManagerTest extends TestCase
{
    use Client;

    /**
     * @var Index
     */
    private $index;

    /**
     * @var IndexManager
     */
    private $indexManager;

    public function test_index_existence_can_be_checked(): void
    {
        $this->createIndex($this->index->getName());
        $this->assertTrue($this->indexManager->exists($this->index));
    }

    public function test_index_can_be_created(): void
    {
        $this->indexManager
            ->create($this->index);

        $this->assertTrue($this->isIndexExists($this->index->getName()));

        // todo check settings and mapping
    }

    public function test_index_can_be_deleted(): void
    {
        $this->createIndex($this->index->getName());

        $this->indexManager
            ->delete($this->index);

        $this->assertFalse($this->isIndexExists($this->index->getName()));
    }

    public function test_non_dynamic_settings_update_causes_exception_without_force(): void
    {
        $this->expectExceptionMessageRegExp('/.*?Can\'t update non dynamic settings.*?/');

        $this->createIndex($this->index->getName());

        $this->indexManager
            ->updateSettings($this->index);
    }

    public function test_settings_can_be_updated_with_force(): void
    {
        $this->createIndex($this->index->getName());

        $this->indexManager
            ->updateSettings($this->index, true);

        // todo check settings
    }

    public function test_mapping_can_be_updated(): void
    {
        $this->createIndex($this->index->getName());

        $this->indexManager
            ->updateMapping($this->index);

       // todo check mapping
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        // todo set settings & mapping
        $this->index = new Index('test');
        $this->indexManager = new IndexManager($this->client);
    }
}
