<?php

namespace Thiagoprz\VegvisirElasticsearch\Adapters;

use Elasticsearch\Client;
use Thiagoprz\Vegvisir\Interfaces\AdapterInterface;

/**
 * Adapter built for Elastic Search integration
 */
class ElasticSearchAdapter implements AdapterInterface
{
    /**
     * Elastic search client
     *
     * @var Client
     */
    private Client $elasticSearch;

    /**
     * @param Client $elasticSearch
     */
    public function __construct(Client $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    /**
     * @param $model
     * @return bool
     */
    public function index($model): bool
    {
        try {
            $response = $this->elasticSearch->index([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
                'body' => $model->toSearchArray(),
            ]);
            $result = $response['result'];
            return $result === 'created' || $result === 'updated';
        } catch (\Exception $e) {
            throw new AdapterException($e->getMessage());
        }
    }

    /**
     * @param $model
     * @return bool
     */
    public function delete($model): bool
    {
        try {
            $response = $this->elasticSearch->delete([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
            ]);
            $result = $response['result'];
            return $result === 'deleted';
        } catch (\Exception $e) {
            throw new AdapterException($e->getMessage());
        }
    }
}
