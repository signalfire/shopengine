<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\Factories\ProductVariantFactory;

class ProductVariant extends Model
{
    use Uuid, HasFactory;
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where([
            ['status', '=', (int)config('shopengine.variant.status.AVAILABLE')],
            ['stock', '>=', (int)config('shopengine.variant.stock.MIN')]
        ]);
    }

    public function scopeAvailableHasStock($query, $stock)
    {
        return $query->where([
            ['status', '=', (int)config('shopengine.variant.status.AVAILABLE')],
            ['stock', '>', $stock]
        ]);
    }

    protected static function newFactory()
    {
        return ProductVariantFactory::new();
    }
}
