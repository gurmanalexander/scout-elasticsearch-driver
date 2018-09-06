<?php
declare(strict_types = 1);

namespace BabenkoIvan\ScoutElasticsearchDriver\Dependencies;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait App
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @before
     */
    public function makeApp(): void
    {
        $this->app = require '/app/laravel/bootstrap/app.php';
        $this->app->make(Kernel::class)->bootstrap();
    }
}
