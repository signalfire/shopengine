<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\PaymentFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Payment extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = ['order_id', 'gateway', 'reference', 'total'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)->firstOrFail();
    }

    protected static function newFactory()
    {
        return PaymentFactory::new();
    }
}
