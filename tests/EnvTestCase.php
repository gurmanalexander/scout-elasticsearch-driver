<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use BabenkoIvan\ScoutElasticsearchDriver\Tests\Stubs\IndexStub;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

abstract class EnvTestCase extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ClientContract
     */
    protected $client;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->app = require '/app/laravel/bootstrap/app.php';
        $this->app->make(Kernel::class)->bootstrap();

        $this->client = resolve(ClientContract::class);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        parent::tearDown();

        $payload = (new Payload())
            ->index((new IndexStub())->getName())
            ->toArray();

        $indices = $this->client->indices();

        if ($indices->exists($payload)) {
            $indices->delete($payload);
        }
    }
}
