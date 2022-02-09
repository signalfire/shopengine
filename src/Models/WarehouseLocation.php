<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\Warehouse;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Factories\WarehouseLocationFactory;

class WarehouseLocation extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = [
        'name', 'notes'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
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
