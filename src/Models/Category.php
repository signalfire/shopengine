<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Factories\CategoryFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Category extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', (int) config('shopengine.category.status.AVAILABLE'));
    }

    public function isAvailable()
    {
        return $this->status === (int) config('shopengine.category.status.AVAILABLE');
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
        return CategoryFactory::new();
    }
}
