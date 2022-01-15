<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\Factories\CategoryFactory;

class Category extends Model
{
    use Uuid, HasFactory;

    protected $fillable = ['name', 'slug', 'status'];
    
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', (int)config('shopengine.category.status.AVAILABLE'));
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
