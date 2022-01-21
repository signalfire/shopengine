<?php

namespace Signalfire\Shopengine;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Illuminate\Support\Arr;
use Signalfire\Shopengine\Nova\Category;
use Signalfire\Shopengine\Nova\Resource;
use Illuminate\Database\Eloquent\Builder;

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

        //Like search macro
        Builder::macro('search', function ($attributes, string $keywords) {
            $this->where(function (Builder $query) use ($attributes, $keywords) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->orWhere(function ($query) use ($attribute, $keywords) {
                        foreach (explode(' ', $keywords) as $keyword) {
                            $query->where($attribute, 'LIKE', "%{$keyword}%");
                        }
                    });
                }
            });
            return $this;
        });

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
