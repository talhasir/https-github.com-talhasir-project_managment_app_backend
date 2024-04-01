<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tasks>
 */
class TasksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->realtext(),
            'due_date' => fake()->dateTimeBetween('now', '+ 1 year'),
            'status' => fake()->randomElement(['pending', 'in_process', 'completed']),
            'status' => fake()->randomElement(['pending', 'in_process', 'completed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'image_path' => fake()->imageUrl(),
            'assigned_user_id' => 1,
            'projects_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'updated_at' => time(),
            'updated_at' => time(),
        ];
    }
}
