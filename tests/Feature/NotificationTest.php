<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_fetch_their_notifications()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by' => $user->id]);
        Notification::factory()->count(3)->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [['id', 'user_id', 'task_id', 'message', 'read', 'created_at', 'updated_at']]
            ]);
    }

    /** @test */
    public function user_can_create_a_notification()
    {
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
    }

    /** @test */
    public function user_cannot_mark_others_notification_as_read()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $notification = Notification::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($user);

        $response = $this->patchJson("/api/v1/notifications/{$notification->id}/read");

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_their_notification()
    {
        $user = User::factory()->create();
        $notification = Notification::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/v1/notifications/{$notification->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    /** @test */
    public function authentication_is_required_to_access_notifications()
    {
        $response = $this->getJson('/api/v1/notifications');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/notifications', [
            'user_id' => 1,
            'message' => 'Teste'
        ]);
        $response->assertStatus(401);

        $response = $this->patchJson('/api/v1/notifications/1/read');
        $response->assertStatus(401);

        $response = $this->deleteJson('/api/v1/notifications/1');
        $response->assertStatus(401);
    }
}
