<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

use Signalfire\Shopengine\Models\Factories\ProductFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Product extends Model implements HasMedia
{
    use Uuid;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'status'];

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

    public function resolveRouteBinding($value, $field = null)
    {
        if (Str::isUuid($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        return $this->where('slug', $value)->firstOrFail();
    }

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
