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

    protected $fillable = ['user_id', 'total', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDispatched($query)
    {
        return $query->whereNotNull('dispatched_at');
    }

    public function scopeNotDispatched($query)
    {
        return $query->whereNull('dispatched_at');
    }

    protected static function newFactory()
    {
        return OrderFactory::new();
    }
}
