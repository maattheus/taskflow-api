<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_user_successfully()
    {
        Sanctum::actingAs(User::factory()->create()); // Usuário autenticado

        $email = 'john' . time() . '@example.com';
        $payload = [
            'name' => 'John Doe',
            'email' => $email,
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/users', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'status', 'data'])
            ->assertJsonPath('message', 'User created successfully');

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    /** @test */
    public function it_should_update_a_user_successfully()
    {
        Sanctum::actingAs(User::factory()->create());
        $user = User::factory()->create();

        $payload = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/v1/users/{$user->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'User updated successfully');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function it_should_fetch_user_by_id_successfully()
    {
        Sanctum::actingAs(User::factory()->create());
        $user = User::factory()->create();

        $response = $this->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'User found successfully')
            ->assertJsonStructure([
                'message',
                'status',
                'data' => ['id', 'name', 'email', 'created_at', 'updated_at']
            ]);
    }

    /** @test */
    public function it_should_return_401_if_not_authenticated()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(401);
    }
}
