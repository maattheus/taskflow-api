<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


it('should fetch notifications for authenticated user', function () {
    // Cria usuário fake
    $user = User::factory()->create();

    // Cria uma task associada (opcional, dependendo do relacionamento)
    $task = Task::factory()->create([
        'created_by' => $user->id
    ]);

    // Cria notificações associadas a esse usuário
    Notification::factory()->count(3)->create([
        'user_id' => $user->id,
        'task_id' => $task->id,
    ]);

    // Simula que esse usuário já está autenticado com Sanctum
    Sanctum::actingAs($user);

    // Faz a requisição para buscar notificações
    $response = $this->getJson('/api/v1/notifications');

    // Verifica se retornou sucesso (200) e a estrutura esperada
    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'status',
            'data' => [['id', 'user_id', 'task_id', 'message', 'read', 'created_at', 'updated_at']]
        ]);
});

it('should create a notification for a user', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/notifications', [
        'user_id' => $user->id,
        'task_id' => $task->id,
        'message' => 'Tarefa atualizada com sucesso.',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment(['message' => 'Tarefa atualizada com sucesso.']);

    $this->assertDatabaseHas('notifications', [
        'user_id' => $user->id,
        'message' => 'Tarefa atualizada com sucesso.'
    ]);
});

it('should not allow a user to mark another user notification as read', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $notification = Notification::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->patchJson("/api/v1/notifications/{$notification->id}/read");

    $response->assertStatus(403); // Forbidden
});


it('should delete a notification', function () {
    $user = User::factory()->create();
    $notification = Notification::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson("/api/v1/notifications/{$notification->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
});

it('should require authentication to fetch notifications', function () {
    $response = $this->getJson('/api/v1/notifications');
    $response->assertStatus(401);
});



