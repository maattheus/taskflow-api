<?php

use App\Models\User;
use App\Models\Project;
use Laravel\Sanctum\Sanctum;

// Teste para buscar projetos de um usuário autenticado
it('should fetch projects for authenticated user', function () {
    $user = User::factory()->create(['role' => 'member']);

    // Autentica o usuário
    Sanctum::actingAs($user);

    // Cria alguns projetos para esse usuário
    Project::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/projects');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'message',
                 'status',
                 'data' => [['id', 'name', 'user_id', 'created_at', 'updated_at']],
             ]);
});

// Teste: admin pode criar projeto
it('should allow admin to create a project', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Sanctum::actingAs($admin);

    $response = $this->postJson('/api/v1/projects', [
        'name' => 'New Project',
    ]);

    $response->assertStatus(200)
             ->assertJson(['message' => 'Project created successfully']);
});

// Teste: usuário comum não pode criar projeto
it('should block non-admin from creating a project', function () {
    $user = User::factory()->create(['role' => 'member']);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/projects', [
        'name' => 'New Project',
    ]);

    $response->assertStatus(403); // Bloqueio para não admin
});

// Teste: admin pode atualizar projeto
it('should allow admin to update a project', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Sanctum::actingAs($admin);

    $project = Project::factory()->create();

    $response = $this->putJson("/api/v1/projects/{$project->id}", [
        'name' => 'Updated Project',
    ]);

    $response->assertStatus(200)
             ->assertJson(['message' => 'Project updated successfully']);
});

// Teste: usuário comum não pode atualizar projeto
it('should block non-admin from updating a project', function () {
    $user = User::factory()->create(['role' => 'member']);
    Sanctum::actingAs($user);

    $project = Project::factory()->create();

    $response = $this->putJson("/api/v1/projects/{$project->id}", [
        'name' => 'Updated Project',
    ]);

    $response->assertStatus(403);
});

// Teste: usuários não autenticados não podem acessar endpoints de projeto
it('should block unauthenticated users from accessing project endpoints', function () {
    $response = $this->getJson('/api/v1/projects');
    $response->assertStatus(401);

    $response = $this->postJson('/api/v1/projects', ['name' => 'Test']);
    $response->assertStatus(401);

    $project = Project::factory()->create();
    $response = $this->putJson("/api/v1/projects/{$project->id}", ['name' => 'Test']);
    $response->assertStatus(401);
});
