<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\BasketFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Basket extends Model
{
    use Uuid;
    use HasFactory;

    public function items()
    {
        return $this->hasMany(BasketItem::class);
    }

    protected static function newFactory()
    {
        return BasketFactory::new();
    }
}
