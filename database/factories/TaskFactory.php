<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'is_completed' => $this->faker->boolean(),
            'completed_at' => $this->faker->boolean() ? now() : null,
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'severity' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'due_at' => $this->faker->boolean() ? now() : null,
        ];
    }
}
