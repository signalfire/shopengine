<?php

namespace Signalfire\Shopengine;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Nova\Category as CategoryResource;
use Signalfire\Shopengine\Nova\Product as ProductResource;
use Signalfire\Shopengine\Nova\ProductVariant as ProductVariantResource;
use Signalfire\Shopengine\Nova\Role as RoleResource;
use Signalfire\Shopengine\Nova\User as UserResource;
use Signalfire\Shopengine\Nova\Address as AddressResource;
use Signalfire\Shopengine\Nova\Order as OrderResource;
use Signalfire\Shopengine\Policies\CategoryPolicy;
use Signalfire\Shopengine\Policies\OrderPolicy;
use Signalfire\Shopengine\Policies\ProductPolicy;
use Signalfire\Shopengine\Policies\ProductVariantPolicy;
use Signalfire\Shopengine\Policies\RolePolicy;

class ShopEngineServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class        => CategoryPolicy::class,
        Product::class         => ProductPolicy::class,
        ProductVariant::class  => ProductVariantPolicy::class,
        Role::class            => RolePolicy::class,
        Address::class         => AddressPolicy::class,
        Order::class           => OrderPolicy::class,
    ];

    public function boot()
    {
        // Load views etc
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shopengine');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish views and config
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/shopengine'),
        ], 'shopengine-views');

        // Publish config
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
            CategoryResource::class,
            ProductResource::class,
            ProductVariantResource::class,
            RoleResource::class,
            UserResource::class,
            AddressResource::class,
            OrderResource::class,
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
