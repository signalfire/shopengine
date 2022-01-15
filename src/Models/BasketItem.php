<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\BasketItemFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class BasketItem extends Model
{
    use Uuid;
    use HasFactory;

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
