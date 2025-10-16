<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'message' => $this->faker->sentence,
            'read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
