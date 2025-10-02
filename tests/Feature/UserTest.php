<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

/**
 * Testa a criação de usuário via API
 */

it('should create a user successfully', function () {
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

    // Confirma que o usuário foi realmente criado no banco
    $this->assertDatabaseHas('users', [
        'email' => $email,
    ]);
});

/**
 * Testa atualização de usuário
 */
it('should update a user successfully', function () {
    Sanctum::actingAs(User::factory()->create()); // Usuário autenticado
    $user = User::factory()->create();

    $payload = [
        'name' => 'Updated Name',
    ];

    $response = $this->putJson("/api/v1/users/{$user->id}", $payload);

    $response->assertStatus(200)
        ->assertJsonPath('message', 'User updated successfully');

    // Verifica se o nome foi alterado no banco
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
    ]);
});

/**
 * Testa a busca de usuário por ID
 */
it('should fetch user by id successfully', function () {
    Sanctum::actingAs(User::factory()->create()); // Usuário autenticado
    $user = User::factory()->create();

    $response = $this->getJson("/api/v1/users/{$user->id}");

    $response->assertStatus(200)
        ->assertJsonPath('message', 'User found successfully')
        ->assertJsonStructure(['message', 'status', 'data' => ['id', 'name', 'email', 'created_at', 'updated_at']]);
});

/**
 * Testa que o acesso sem autenticação retorna 401
 */
it('should return 401 if not authenticated', function () {
    $user = User::factory()->create();

    // Tentando acessar sem token
    $response = $this->getJson("/api/v1/users/{$user->id}");
    $response->assertStatus(401);
});
