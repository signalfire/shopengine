<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\WarehouseLocation;
use Signalfire\Shopengine\Factories\WarehouseFactory;

class Warehouse extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = [
        'name', 'notes'
    ];

    public function locations()
    {
        return $this->hasMany(WarehouseLocation::class);
    }

    protected static function newFactory()
    {
        return WarehouseFactory::new();
    }
}
