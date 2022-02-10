<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\WarehouseLocationFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class WarehouseLocation extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = [
        'name', 'notes',
    ];

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    protected static function newFactory()
    {
        return WarehouseLocationFactory::new();
    }
}
