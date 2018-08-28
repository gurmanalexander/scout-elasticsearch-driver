<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Searchable;
use Illuminate\Database\Eloquent\Model;

class ModelStub extends Model
{
    use Searchable;

    /**
     * @var Index
     */
    private $searchableIndex;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'price'
    ];

    /**
     * @param array $attributes
     * @param Index|null $searchableIndex
     */
    public function __construct(array $attributes = [], Index $searchableIndex = null)
    {
        parent::__construct($attributes);

        $this->searchableIndex = $searchableIndex ?? new IndexStub();
    }

    /**
     * @inheritdoc
     */
    public function getSearchableIndex(): Index
    {
        return $this->searchableIndex;
    }
}
