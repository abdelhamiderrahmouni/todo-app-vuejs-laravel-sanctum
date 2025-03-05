<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'jhondoe@gmail.com',
        ]);

        $formData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->post('/api/login', $formData)
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_user_can_register(): void
    {
        $formData = [
            'name' => 'Jhon Doe',
            'email' => 'jhondoe@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/api/register', $formData)
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'jhondoe@gmail.com',
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->post('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ])
        ->assertStatus(200)
        ->assertJsonStructure(['message']);
    }
}
