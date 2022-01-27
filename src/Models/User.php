<?php

namespace Signalfire\Shopengine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Signalfire\Shopengine\Models\Factories\UserFactory;
use Signalfire\Shopengine\Models\Traits\Uuid;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\Address;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function isAdmin()
    {
        $admin = Role::where('name', 'admin')->first();

        return $this->roles->contains($admin);
    }

    public function isCustomer()
    {
        $customer = Role::where('name', 'customer')->first();

        return $this->roles->contains($customer);
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
