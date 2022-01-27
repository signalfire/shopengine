<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\OrderItemFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class OrderItem extends Model
{
    use Uuid;
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    protected static function newFactory()
    {
        return OrderItemFactory::new();
    }
}
