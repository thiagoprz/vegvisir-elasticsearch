<?php

namespace Thiagoprz\VegvisirElasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Thiagoprz\Vegvisir\Interfaces\AdapterInterface;
use Thiagoprz\Vegvisir\VegvisirServiceProvider;
use Thiagoprz\VegvisirElasticsearch\Adapters\ElasticSearchAdapter;

class VegvisirElasticSearchServiceProvider extends VegvisirServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('vegvisir.adapters.elastic_search.hosts'))
                ->build();
        });
        $this->app->bind(AdapterInterface::class, ElasticSearchAdapter::class);
    }
}
