<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Core;

use BabenkoIvan\ScoutElasticsearchDriver\Tests\AppTestCase;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\ModelStub;
use Illuminate\Support\Collection;

class SearchableTest extends AppTestCase
{
    public function testToSearchableDocumentMethod(): void
    {
        $model = new ModelStub([
            'id' => 1,
            'name' => 'iphone 8',
            'price' => 800
        ]);

        $document = $model->toSearchableDocument();

        $this->assertSame($model->getKey(), $document->getId());
        $this->assertSame($model->getAttributes(), $document->getFields()->toArray());
    }

    public function testGetSearchableIndicesMethod(): void
    {
        $iphone6s = new ModelStub(
            [
                'id' => 1,
                'name' => 'iphone 6s',
                'price' => 300
            ],
            new IndexStub('old')
        );

        $iphoneX = new ModelStub(
            [
                'id' => 2,
                'name' => 'iphone x',
                'price' => 1000
            ],
            new IndexStub('new')
        );

        /** @var Collection $documents */
        $indices = collect([$iphone6s, $iphoneX])->getSearchableIndices();

        $this->assertSame(['old', 'new'], $indices->keys()->all());
        $this->assertEquals($indices->get('old'), $iphone6s->getSearchableIndex());
        $this->assertEquals($indices->get('new'), $iphoneX->getSearchableIndex());
    }

    public function testToSearchableDocumentsMethod(): void
    {
        $iphone6s = new ModelStub(
            [
                'id' => 1,
                'name' => 'iphone 6s',
                'price' => 300
            ],
            new IndexStub('old')
        );

        $iphoneX = new ModelStub(
            [
                'id' => 2,
                'name' => 'iphone x',
                'price' => 1000
            ],
            new IndexStub('new')
        );

        /** @var Collection $documents */
        $documents = collect([$iphone6s, $iphoneX])->toSearchableDocuments();

        $this->assertSame(['old', 'new'], $documents->keys()->all());
        $this->assertInstanceOf(Collection::class, $documents->get('old'));
        $this->assertInstanceOf(Collection::class, $documents->get('new'));
        $this->assertCount(1, $documents->get('old'));
        $this->assertCount(1, $documents->get('new'));
    }
}
