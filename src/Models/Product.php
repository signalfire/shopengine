<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Signalfire\Shopengine\Models\BasketItem;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\Factories\ProductFactory;

class Product extends Model
{
    use Uuid, HasFactory;
    
    public function items()
    {
        return $this->hasMany(BasketItem::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', (int)config('shopengine.product.status.AVAILABLE'));
    }

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
