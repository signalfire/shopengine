<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Signalfire\Shopengine\Models\User;
use Signalfire\Shopengine\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::factory()->state([
            'name' => 'admin'
        ])->create();

        $user = User::factory()->state([
            'id' => 1,
            'email' => 'admin@example.com',
            'password' => Hash::make('internet'),
        ])->create();

        $user->roles()->attach($role);
    }
}
