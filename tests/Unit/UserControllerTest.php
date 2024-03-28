<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegistration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'User Created Successfully'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
    }

    public function testUserLogin()
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password)
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'access_token'
            ]);
    }

    public function testUserLogout()
    {
        $user = User::factory()->create();
        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Logged out'
            ]);

        $this->assertEmpty($user->tokens);
    }
}
