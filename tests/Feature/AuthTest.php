<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
            use RefreshDatabase;

    public function test_user_can_register(){
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phone' => '1234567890',
            'password' => 'password123'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
    }

    public function test_user_can_login(){
        User::factory()->create([
            'email' => 'testuser@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        }
    public function test_user_cant_register_with_existing_email(){
    $email = User::factory()->create()->email;

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' =>  $email,
            'phone' => '1234567890',
            'password' => 'password123'
        ], ['Accept' => 'application/json']);
        $response->assertStatus(422);
    }
    public function test_user_cant_register_with_existing_phone(){
    $existingPhone = User::factory()->create()->phone;

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phone' => $existingPhone,
            'password' => 'password123'
        ], ['Accept' => 'application/json']);
        $response->assertStatus(422);
    }
}
