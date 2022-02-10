<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\WarehouseFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Warehouse extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = [
        'name', 'notes',
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
