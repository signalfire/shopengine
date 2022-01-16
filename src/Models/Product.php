<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Signalfire\Shopengine\Models\Factories\ProductFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Product extends Model
{
    use Uuid;
    use HasFactory;
    use Searchable;

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
        return $query->where('status', (int) config('shopengine.product.status.AVAILABLE'));
    }

    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Set fields to use

        return $array;
    }
}
