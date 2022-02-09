<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Factories\ProductFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Signalfire\Shopengine\Models\WarehouseLocation;

class Product extends Model implements HasMedia
{
    use Uuid;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'description', 'status'];

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

    public function locations()
    {
        return $this->belongsToMany(WarehouseLocation::class);
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(300)
              ->height(300);
    }

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
