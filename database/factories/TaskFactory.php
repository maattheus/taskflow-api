<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'board_id' => Board::factory(),
            'created_by' => User::factory(),       // substitui user_id
            'assigned_to' => User::factory(),
            'priority' => $this->faker->numberBetween(1, 3),
            'due_date' => $this->faker->date(),
        ];
    }
}
