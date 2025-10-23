<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_add_a_member_to_a_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $newMember = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/v1/projects/{$project->id}/members", [
            'user_id' => $newMember->id
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'User added to project successfully');

        $this->assertTrue($project->members()->where('user_id', $newMember->id)->exists());
    }

    /** @test */
    public function non_admin_cannot_add_a_member_to_a_project()
    {
        $user = User::factory()->create(['role' => 'user']);
        $newMember = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/projects/{$project->id}/members", [
            'user_id' => $newMember->id
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_remove_a_member_from_a_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create();
        $project = Project::factory()->create();

        $project->members()->attach($member->id);

        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/v1/projects/{$project->id}/members", [
            'user_id' => $member->id
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'User removed from project successfully');

        $this->assertFalse($project->members()->where('user_id', $member->id)->exists());
    }

    /** @test */
    public function non_admin_cannot_remove_a_member_from_a_project()
    {
        $user = User::factory()->create(['role' => 'user']);
        $member = User::factory()->create();
        $project = Project::factory()->create();

        $project->members()->attach($member->id);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/v1/projects/{$project->id}/members", [
            'user_id' => $member->id
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_can_fetch_all_project_members()
    {
        $user = User::factory()->create();
        $members = User::factory(3)->create();
        $project = Project::factory()->create();

        $project->members()->attach($members->pluck('id')->toArray());

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/projects/{$project->id}/members");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Project members fetched successfully')
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function unauthenticated_users_cannot_access_project_members_endpoints()
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/v1/projects/{$project->id}/members");
        $response->assertStatus(401);

        $response = $this->postJson("/api/v1/projects/{$project->id}/members", [
            'user_id' => 1
        ]);
        $response->assertStatus(401);

        $response = $this->deleteJson("/api/v1/projects/{$project->id}/members", [
            'user_id' => 1
        ]);
        $response->assertStatus(401);
    }
}
