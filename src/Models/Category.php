<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
