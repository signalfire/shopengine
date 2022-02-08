<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\OrderFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Order extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = ['user_id', 'total', 'status', 'dispatched_at'];

    protected $casts = [
        'dispatched_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cardholder()
    {
        return $this->belongsTo(Address::class, 'cardholder_address_id');
    }

    public function delivery()
    {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeDispatched($query)
    {
        return $query->whereNotNull('dispatched_at');
    }

    public function scopeNotDispatched($query)
    {
        return $query->whereNull('dispatched_at');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)->firstOrFail();
    }

    protected static function newFactory()
    {
        return OrderFactory::new();
    }
}
