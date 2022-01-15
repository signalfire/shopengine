<?php

namespace Signalfire\Shopengine;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Signalfire\Shopengine\Nova\Category;
use Signalfire\Shopengine\Nova\Resource;

class ShopEngineServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views etc
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shopengine');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish views and config
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/shopengine'),
        ], 'shopengine-views');

        $this->publishes([
            __DIR__.'/../config/shopengine.php' => base_path('config/shopengine.php'),
        ], 'shopengine-config');

        // publish models

        Nova::resources([
            Resource::class,
            Category::class,
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shopengine.php', 'shopengine');

        $this->app->bind('shopengine', function () {
            return new ShopEngine();
        });
    }
}
