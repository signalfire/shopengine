<?php

namespace Signalfire\Shopengine;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\OrderItem;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Nova\Resources\Address as AddressResource;
use Signalfire\Shopengine\Nova\Resources\Category as CategoryResource;
use Signalfire\Shopengine\Nova\Resources\Item as OrderItemResource;
use Signalfire\Shopengine\Nova\Resources\Order as OrderResource;
use Signalfire\Shopengine\Nova\Resources\Payment as PaymentResource;
use Signalfire\Shopengine\Nova\Resources\Product as ProductResource;
use Signalfire\Shopengine\Nova\Resources\Role as RoleResource;
use Signalfire\Shopengine\Nova\Resources\User as UserResource;
use Signalfire\Shopengine\Nova\Resources\Variant as VariantResource;
use Signalfire\Shopengine\Nova\Resources\Warehouse as WarehouseResource;
use Signalfire\Shopengine\Nova\Resources\WarehouseLocation as WarehouseLocationResource;
use Signalfire\Shopengine\Policies\AddressPolicy;
use Signalfire\Shopengine\Policies\CategoryPolicy;
use Signalfire\Shopengine\Policies\OrderItemPolicy;
use Signalfire\Shopengine\Policies\OrderPolicy;
use Signalfire\Shopengine\Policies\PaymentPolicy;
use Signalfire\Shopengine\Policies\ProductPolicy;
use Signalfire\Shopengine\Policies\ProductVariantPolicy;
use Signalfire\Shopengine\Policies\RolePolicy;
use Signalfire\Shopengine\Policies\UserPolicy;
use Signalfire\Shopengine\Policies\WarehouseLocationPolicy;
use Signalfire\Shopengine\Policies\WarehousePolicy;
use Signalfire\Shopengine\Interfaces\AddressRepositoryInterface;
use Signalfire\Shopengine\Repositories\AddressRepository;
use Signalfire\Shopengine\Interfaces\BasketRepositoryInterface;
use Signalfire\Shopengine\Repositories\BasketRepository;
use Signalfire\Shopengine\Interfaces\CategoryRepositoryInterface;
use Signalfire\Shopengine\Repositories\CategoryRepository;

class ShopEngineServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class          => CategoryPolicy::class,
        Product::class           => ProductPolicy::class,
        ProductVariant::class    => ProductVariantPolicy::class,
        Role::class              => RolePolicy::class,
        Address::class           => AddressPolicy::class,
        Order::class             => OrderPolicy::class,
        OrderItem::class         => OrderItemPolicy::class,
        User::class              => UserPolicy::class,
        Payment::class           => PaymentPolicy::class,
        Warehouse::class         => WarehousePolicy::class,
        WarehouseLocation::class => WarehouseLocationPolicy::class,
        Address::class           => AddressPolicy::class,
    ];

    public function boot()
    {
        // Load views etc
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shopengine');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

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
            VariantResource::class,
            RoleResource::class,
            UserResource::class,
            AddressResource::class,
            OrderResource::class,
            OrderItemResource::class,
            PaymentResource::class,
            WarehouseResource::class,
            WarehouseLocationResource::class,
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shopengine.php', 'shopengine');

        $this->app->register('Spatie\MediaLibrary\MediaLibraryServiceProvider');
        $this->app->register('Barryvdh\DomPDF\ServiceProvider');
        $this->app->register('Ebess\AdvancedNovaMediaLibrary\AdvancedNovaMediaLibraryServiceProvider');

        $this->app->bind('shopengine', function () {
            return new ShopEngine();
        });

        $this->app->bind(BasketRepositoryInterface::class, BasketRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }
}
