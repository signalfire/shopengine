<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Signalfire\Shopengine\Models\Basket;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\Factories\BasketItemFactory;

class BasketItem extends Model
{
    use Uuid, HasFactory;
        
    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory()
    {
        return BasketItemFactory::new();
    }
}
