# Vegvisir Elastic Search - Laravel Elasticsearch Indexer
[![PHPUnit](https://github.com/thiagoprz/vegvisir-elasticsearch/actions/workflows/phpunit.yml/badge.svg?branch=main)](https://github.com/thiagoprz/vegvisir-elasticsearch/actions/workflows/phpunit.yml)

This is the elastic search adapter for the [vegvisir](https://github.com/thiagoprz/vegvisir) package which provides ability to implement search tools for boosting search performance on laravel applications.
Supports direct implementation using Eloquent but also gives the possibility to use Repositories as an alternative approach.


## Table of contents
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
* [Contributing](#contributing)
* [Testing](#testing)
* [Support](#support)
    - [Issues](#issues)
* [License](#license)

## Installation
Install it using composer on your application:
`composer require thiagoprz/vegvisir-elasticsearch`


## Configuration
If necessary add the service provider to `config/app.php` (if auto discovery is enabled this not necessary):
```
..
'providers' => [
...
    \Thiagoprz\VegvisirElasticsearch\VegvisirElasticSearchServiceProvider::class,
],
...
```

Publish configuration by running
`php artisan vendor:publish`.

This will add the `vegvisir.php` file to the `config` directory. 

## Usage
This adapter connects to Elasticsearch host(s) and will insert/update/delete everytime changes happen to your model.

To enable Elasticsearch Adapter you just have to add the related Service provider to your app configuration and also define the environmental variable related to your Elasticsearch hosts. 

1) Enable VegvisirElasticSearchServiceProvider on config/app.php
```
<?php
...
    'providers' => [
        ...
        Thiagoprz\VegvisirElasticsearch\VegvisirElasticSearchServiceProvider::class, 
    ],
...
```


2) Add the following variables to your .env file for configuring Elasticsearch access:
```
VEGVISIR_ELASTICSEARCH_HOSTS=0.0.0.0:9200,0.0.0.1:9200
```
You can add as many Elasticsearch hosts as necessary.

3) Add Searchable trait to your model and establish searchable fields:
```
use Thiagoprz\Vegvisir\Traits\Searchable;

class Post extends Model
{
    use Searchable;

    ...
    
    /**
     * This method is optional, but recommended in case of tables with too many fields or fields that won't be searched
     */
    public function toSearchArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    
    /**
     * Defines fields to be searched
     */
    public function getSearchFields(): array
    {
        return ['title', 'description'];
    }
}
```

4) Attach event observer to it on `EventServiceProvider`:

```
use Thiagoprz\Vegvisir\Observers\VegvisirObserver;
use App\Models\Post;
...
class EventServiceProvider extends ServiceProvider
{
    ...
    public function boot()
    {
        Post::observe(VegvisirObserver::class);
    }
```

5) Create your model's repository (optional):

```
namespace App\Repositories;

use App\Models\Post;
use Thiagoprz\VegvisirElasticsearch\Repositories\ElasticSearchRepository;

class PostRepository extends ElasticSearchRepository
{
    /**
     * Repository model class
     */
    public $modelClass = Post::class;
} 
```

## Contributing

## Testing

```
vendor/phpunit/phpunit/phpunit --configuration phpunit.xml tests
```

## Support

### Issues
Please feel free to [create issues](https://github.com/thiagoprz/vegvisir-elasticsearch/issues) on this package, it will help a lot. I will address it as soon as possible.

## License
This package is licensed under the
[MIT](License.txt) license.
