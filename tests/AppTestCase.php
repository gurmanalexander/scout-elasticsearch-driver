<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

class AppTestCase extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->app = require '/app/laravel/bootstrap/app.php';
        $this->app->make(Kernel::class)->bootstrap();
    }
}
