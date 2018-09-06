<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Fixtures;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Entities\Index;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Searchable;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
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
        $this->searchableIndex = $searchableIndex ?? new Index('test');
    }

    /**
     * @inheritdoc
     */
    public function getSearchableIndex(): Index
    {
        return $this->searchableIndex;
    }
}
