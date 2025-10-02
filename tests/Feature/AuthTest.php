<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

/**
 * Teste: login com credenciais válidas
 */
it('should login successfully with valid credentials', function () {
    // Cria um usuário fake no banco de testes com senha "password123" já hasheada
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    // Faz a requisição de login para a API
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    // Verifica se deu sucesso (200) e se retornou as chaves "message" e "token"
    $response->assertStatus(200)
             ->assertJsonStructure(['message', 'token']);
});

/**
 * Teste: login falha com senha incorreta
 */
it('should fail login with wrong password', function () {
    // Cria usuário com senha correta
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    // Tenta logar com senha errada
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrongpass',
    ]);

    // Espera que a API retorne 401 (Unauthorized) e mensagem de erro
    $response->assertStatus(401)
             ->assertJson(['message' => 'Invalid credentials.']);
});

/**
 * Teste: logout funciona com token válido
 */
it('should logout successfully with valid token', function () {
    // Cria usuário fake
    $user = User::factory()->create();

    // Simula que esse usuário já está autenticado com Sanctum
    Sanctum::actingAs($user);

    // Faz logout
    $response = $this->postJson('/api/v1/auth/logout');

    // Verifica se retornou sucesso (200) e a mensagem de logout
    $response->assertStatus(200)
             ->assertJson(['message' => 'User logout successfully']);
});

/**
 * Teste: logout sem token retorna erro
 */
it('should return 401 if trying to logout without token', function () {
    // Faz logout sem estar autenticado
    $response = $this->postJson('/api/v1/auth/logout');

    // Espera que a API bloqueie (401 Unauthorized)
    $response->assertStatus(401);
});
