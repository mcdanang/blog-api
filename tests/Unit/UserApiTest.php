<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
  use RefreshDatabase;

  public function test_can_register_user()
  {
    $userData = [
      'name' => 'Test User',
      'email' => 'testuser@example.com',
      'password' => 'P@ssw0rd123'
    ];

    $response = $this->postJson('/api/register', $userData);

    $response->assertStatus(201)
      ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token'])
      ->assertJson(['user' => ['name' => $userData['name'], 'email' => $userData['email']]]);

    $this->assertDatabaseHas('users', [
      'name' => $userData['name'],
      'email' => $userData['email']
    ]);
  }

  public function test_register_validation_failures()
  {
    $response = $this->postJson('/api/register', []);

    $response->assertStatus(422)
      ->assertJsonStructure(['message', 'errors'])
      ->assertJson([
        'message' => 'Validation failed',
        'errors' => [
          'name' => ['The name field is required.'],
          'email' => ['The email field is required.'],
          'password' => ['The password field is required.']
        ]
      ]);
  }

  public function test_can_login_user()
  {
    $user = User::factory()->create([
      'password' => Hash::make('P@ssw0rd123')
    ]);

    $response = $this->postJson('/api/login', [
      'email' => $user->email,
      'password' => 'P@ssw0rd123'
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token'])
      ->assertJson(['user' => ['email' => $user->email]]);
  }

  public function test_login_with_invalid_credentials()
  {
    $response = $this->postJson('/api/login', [
      'email' => 'nonexistent@example.com',
      'password' => 'wrongpassword'
    ]);

    $response->assertStatus(401)
      ->assertJson(['message' => 'Invalid credentials']);
  }
}
