<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

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
            'name' => 'admin',
        ])->create();

        $user = User::factory()->state([
            'id'       => '5e4ad4ad-6fee-4ff3-8e06-163e5166c625',
            'email'    => 'admin@example.com',
            'password' => Hash::make('internet'),
        ])->create();

        $user->roles()->attach($role);
    }
}
