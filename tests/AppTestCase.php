<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Payload;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

abstract class AppTestCase extends TestCase
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

    private function dropIndices(): void
    {
        $payload = (new Payload())->index('*');

        $this->client->indices()
            ->delete($payload->toArray());
    }

    protected function dropTables(): void
    {
        // todo
    }

    private function cleanup(): void
    {
        $this->dropIndices();
        $this->dropTables();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->cleanup();
    }
}
