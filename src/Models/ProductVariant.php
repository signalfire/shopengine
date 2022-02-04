<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\ProductVariantFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class ProductVariant extends Model implements HasMedia
{
    use Uuid;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'product_id', 'barcode', 'name', 'slug', 'stock', 'price', 'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where([
            ['status', '=', (int) config('shopengine.variant.status.AVAILABLE')],
            ['stock', '>=', (int) config('shopengine.variant.stock.MIN')],
        ]);
    }

    public function scopeAvailableHasStock($query, $stock)
    {
        return $query->where([
            ['status', '=', (int) config('shopengine.variant.status.AVAILABLE')],
            ['stock', '>', $stock],
        ]);
    }

    protected static function newFactory()
    {
        return ProductVariantFactory::new();
    }
}
