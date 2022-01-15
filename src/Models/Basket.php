<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Signalfire\Shopengine\Models\BasketItem;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\Factories\BasketFactory;

class Basket extends Model
{
    use Uuid, HasFactory;
    
    public function items()
    {
        return $this->hasMany(BasketItem::class);
    }

    protected static function newFactory()
    {
        return BasketFactory::new();
    }
}
