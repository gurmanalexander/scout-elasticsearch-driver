<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Fixtures\Model;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class SearchableTest extends TestCase
{
    public function test_model_can_be_converted_to_searchable_document(): void
    {
        $model = new Model([
            'id' => 1,
            'name' => 'iphone 8',
            'price' => 800
        ]);

        $document = $model->toSearchableDocument();

        $this->assertSame(strval($model->getKey()), $document->getId());
        $this->assertSame($model->getAttributes(), $document->getContent()->all());
    }

    public function test_model_collection_can_be_converted_to_document_collection(): void
    {
        $iphone6s = new Model(
            [
                'id' => 1,
                'name' => 'iphone 6s',
                'price' => 300
            ],
            new Index('old')
        );

        $iphoneX = new Model(
            [
                'id' => 2,
                'name' => 'iphone x',
                'price' => 1000
            ],
            new Index('new')
        );

        /** @var Collection $documents */
        $documents = collect([$iphone6s, $iphoneX])->toSearchableDocuments();

        $this->assertSame(['old', 'new'], $documents->keys()->all());
        $this->assertInstanceOf(Collection::class, $documents->get('old'));
        $this->assertInstanceOf(Collection::class, $documents->get('new'));
        $this->assertCount(1, $documents->get('old'));
        $this->assertCount(1, $documents->get('new'));
    }

    public function test_searchable_indices_can_be_collected_from_model_collection(): void
    {
        $iphone6s = new Model(
            [
                'id' => 1,
                'name' => 'iphone 6s',
                'price' => 300
            ],
            new Index('old')
        );

        $iphoneX = new Model(
            [
                'id' => 2,
                'name' => 'iphone x',
                'price' => 1000
            ],
            new Index('new')
        );

        /** @var Collection $documents */
        $indices = collect([$iphone6s, $iphoneX])->getSearchableIndices();

        $this->assertSame(['old', 'new'], $indices->keys()->all());
        $this->assertEquals($indices->get('old'), $iphone6s->getSearchableIndex());
        $this->assertEquals($indices->get('new'), $iphoneX->getSearchableIndex());
    }
}
