<?php

namespace Thiagoprz\VegvisirElasticsearch\Repositories;

use Elasticsearch\Client;
use Thiagoprz\Vegvisir\Interfaces\Collection;
use Thiagoprz\Vegvisir\Interfaces\RepositoryInterface;

class ElasticSearchRepository implements RepositoryInterface
{
    public $modelClass;
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(string $query = ''): Collection
    {
        $model = new $this->modelClass();
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => $this->getSearchFields(),
                        'query' => $query,
                    ],
                ],
            ],
        ]);
        return $this->buildCollection($items);
    }

    public function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return $this->modelClass::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }
}
