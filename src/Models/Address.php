<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalfire\Shopengine\Models\Factories\AddressFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;

class Address extends Model
{
    use Uuid;
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'forename', 'surname', 'address1',
        'address2', 'address3', 'towncity', 'county', 'postalcode',
        'country'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return AddressFactory::new();
    }
}
