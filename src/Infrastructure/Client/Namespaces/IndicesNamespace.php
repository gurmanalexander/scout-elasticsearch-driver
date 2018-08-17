<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\Client\Namespaces;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Namespaces\IndicesNamespace as IndicesNamespaceContract;
use Elasticsearch\Namespaces\IndicesNamespace as AdapteeIndicesNamespace;

class IndicesNamespace implements IndicesNamespaceContract
{
    /**
     * @var AdapteeIndicesNamespace
     */
    private $adapteeIndicesNamespace;

    /**
     * @param AdapteeIndicesNamespace $adapteeIndicesNamespace
     */
    public function __construct(AdapteeIndicesNamespace $adapteeIndicesNamespace)
    {
        $this->adapteeIndicesNamespace = $adapteeIndicesNamespace;
    }

    /**
     * @inheritdoc
     */
    public function exists(array $params): bool
    {
        return $this->adapteeIndicesNamespace
            ->exists($params);
    }

    /**
     * @inheritdoc
     */
    public function create(array $params): array
    {
        return $this->adapteeIndicesNamespace
            ->create($params);
    }

    /**
     * @inheritdoc
     */
    public function delete(array $params): array
    {
        return $this->adapteeIndicesNamespace
            ->delete($params);
    }

    /**
     * @inheritdoc
     */
    public function putSettings(array $params): array
    {
        return $this->adapteeIndicesNamespace
            ->putSettings($params);
    }

    /**
     * @inheritdoc
     */
    public function putMapping(array $params): array
    {
        return $this->adapteeIndicesNamespace
            ->putMapping($params);
    }

    /**
     * @inheritdoc
     */
    public function open(array $params): array
    {
        return $this->adapteeIndicesNamespace
            ->open($params);
    }

    /**
     * @inheritdoc
     */
    public function close(array $params): array
    {
        return $this->adapteeIndicesNamespace
            ->close($params);
    }
}
