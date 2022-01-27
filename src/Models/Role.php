<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\RoleFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Role extends Model
{
    use HasFactory;
    use Uuid;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    protected static function newFactory()
    {
        return RoleFactory::new();
    }
}
