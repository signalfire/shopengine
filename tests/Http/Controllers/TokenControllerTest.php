<?php

namespace Signalfire\Shopengine\Tests;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

use Signalfire\Shopengine\Models\User;

class TokenControllerTest extends TestCase
{
    public function testItLogsInAndGetsToken()
    {

        $user = User::factory()->state([
            'password' => Hash::make('testing')
        ])->create();

        $this->post('/api/token', [
            'email' => $user->email,
            'password' => 'testing'
        ])
        ->assertJsonStructure([
            'data' => [
                'token'
            ]
        ])
        ->assertStatus(201);
    }

    // public function testItFailsToLogInAndGetToken() {

    //     $user = User::factory()->create();

    //     $this->post('/api/token', [
    //         'email' => 'test@example.com',
    //         'password' => 'testing'
    //     ])
    //     ->assertJsonValidationErrorFor('email', 'errors')
    //     ->assertStatus(422);
    // }
}
