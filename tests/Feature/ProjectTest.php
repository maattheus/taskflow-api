<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_fetch_their_projects()
    {
        $user = User::factory()->create(['role' => 'member']);
        Project::factory()->count(3)->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [['id', 'name', 'user_id', 'created_at', 'updated_at']],
            ]);
    }

    /** @test */
    public function admin_can_create_a_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/projects', [
            'name' => 'New Project',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Project created successfully']);
    }

    /** @test */
    public function non_admin_cannot_create_a_project()
    {
        $user = User::factory()->create(['role' => 'member']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects', [
            'name' => 'New Project',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_a_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/v1/projects/{$project->id}", [
            'name' => 'Updated Project',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Project updated successfully']);
    }

    /** @test */
    public function non_admin_cannot_update_a_project()
    {
        $user = User::factory()->create(['role' => 'member']);
        $project = Project::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/v1/projects/{$project->id}", [
            'name' => 'Updated Project',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_project_endpoints()
    {
        $project = Project::factory()->create();

        $response = $this->getJson('/api/v1/projects');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/projects', ['name' => 'Test']);
        $response->assertStatus(401);

        $response = $this->putJson("/api/v1/projects/{$project->id}", ['name' => 'Test']);
        $response->assertStatus(401);
    }
}
