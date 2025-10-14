<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Board;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create()->each(function ($user) {
            Project::factory(2)->create(['user_id' => $user->id])->each(function ($project) {
                Board::factory(3)->create(['project_id' => $project->id])->each(function ($board) use ($project) {
                    Task::factory(4)->create([
                        'board_id' => $board->id,
                        'created_by' => $project->user_id,
                        'assigned_to' => $project->user_id
                    ]);
                });
            });
        });
    }
}
