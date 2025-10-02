<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BoardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_fetch_boards_for_authenticated_user()
    {
        // Criar usuário e projeto
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        // Criar alguns boards
        $boards = Board::factory()->count(3)->create(['project_id' => $project->id]);

        // Autenticar
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson("/api/v1/boards/{$project->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'status',
                     'data' => [['id', 'name', 'project_id', 'created_at', 'updated_at']]
                 ]);
    }

    /** @test */
    public function it_should_create_a_board_for_authenticated_user()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/v1/boards', [
            'name' => 'New Board',
            'project_id' => $project->id
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Board created successfully');
    }

    /** @test */
    public function it_should_update_a_board_for_authenticated_user()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $board = Board::factory()->create(['project_id' => $project->id]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->putJson("/api/v1/boards/{$board->id}", [
            'name' => 'Updated Board'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Board updated successfully');
    }

    /** @test */
    public function it_should_block_unauthenticated_users()
    {
        $project = Project::factory()->create();
        $board = Board::factory()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/v1/boards/{$project->id}");
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/boards', [
            'name' => 'Test Board',
            'project_id' => $project->id
        ]);
        $response->assertStatus(401);

        $response = $this->putJson("/api/v1/boards/{$board->id}", [
            'name' => 'Test Update'
        ]);
        $response->assertStatus(401);
    }
}
