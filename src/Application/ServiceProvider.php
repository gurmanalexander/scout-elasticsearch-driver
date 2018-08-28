<?php

namespace BabenkoIvan\ScoutElasticsearchDriver\Application;

use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\Client as ClientContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\Client\ClientFactory as ClientFactoryContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\DocumentManager as DocumentManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Contracts\EntityManagers\IndexManager as IndexManagerContract;
use BabenkoIvan\ScoutElasticsearchDriver\Core\Engine;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\Client\ClientFactory;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers\BulkDocumentManager;
use BabenkoIvan\ScoutElasticsearchDriver\Infrastructure\EntityManagers\IndexManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Scout\EngineManager;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->registerClientFactory();
        $this->registerClient();
        $this->registerIndexManager();
        $this->registerDocumentManager();
    }

    public function boot(): void
    {
        $this->bootConfig();
        $this->bootEngine();
    }

    private function registerClientFactory(): void
    {
        $this->app->bindIf(ClientFactoryContract::class, ClientFactory::class);
    }

    private function registerClient(): void
    {
        $this->app->bindIf(ClientContract::class, function () {
            $config = config('scout_elasticsearch_driver.client', []);
            $clientBuilder = $this->app->make(ClientFactoryContract::class);
            return $clientBuilder->fromConfig($config);
        }, true);
    }

    private function registerIndexManager(): void
    {
        $this->app->bindIf(IndexManagerContract::class, IndexManager::class);
    }

    private function registerDocumentManager(): void
    {
        $this->app->bindIf(DocumentManagerContract::class, BulkDocumentManager::class);
    }

    private function bootConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/scout_elasticsearch_driver.php' => config_path('scout_elasticsearch_driver.php')
        ]);
    }

    private function bootEngine(): void
    {
        $engineManager = resolve(EngineManager::class);
        $indexManager = resolve(IndexManagerContract::class);
        $documentManager = resolve(DocumentManagerContract::class);

        $engineManager->extend('elastic', function () use ($indexManager, $documentManager) {
            return new Engine($indexManager, $documentManager);
        });
    }
}
