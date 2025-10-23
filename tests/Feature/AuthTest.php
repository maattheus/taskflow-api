<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'token']);
    }

    /** @test */
    public function login_fails_with_invalid_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);
    }

    /** @test */
    public function login_fails_with_nonexistent_email()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'notfound@example.com',
            'password' => 'any-password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);
    }

    /** @test */
    public function login_fails_when_email_or_password_missing()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'user@example.com',
            // senha ausente
        ]);

        $response->assertStatus(422); // validação Laravel retorna 422
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'User logout successfully']);
    }

    /** @test */
    public function logout_fails_without_authentication()
    {
        $response = $this->postJson('/api/v1/auth/logout');
        $response->assertStatus(401);
    }

    /** @test */
    public function logout_fails_with_invalid_token()
    {
        // Faz a requisição com um token que não existe
        $response = $this->postJson('/api/v1/auth/logout', [], [
            'Authorization' => 'Bearer invalid_token'
        ]);

        $response->assertStatus(401);
    }

}
