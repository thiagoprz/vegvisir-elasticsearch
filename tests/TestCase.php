<?php

namespace Thiagoprz\Onesignal\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thiagoprz\VegvisirElasticsearch\VegvisirElasticSearchServiceProvider;

class TestCase extends OrchestraTestCase
{

    /**
     * @param $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            VegvisirElasticSearchServiceProvider::class,
        ];
    }

    /**
     * @param $app
     * @return void
     */
    public function getEnvironmentSetUp($app)
    {
        $app->loadEnvironmentFrom('.env.testing');
    }
}