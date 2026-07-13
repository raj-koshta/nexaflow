<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
class ProjectFactory extends Factory {
    public function definition(): array {
        return [
            'project_code' => 'PRJ-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'name' => fake()->catchPhrase(),
            'client_id' => Client::factory(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 week'),
            'due_date' => fake()->dateTimeBetween('+1 month', '+6 months'),
            'budget' => fake()->randomFloat(2, 5000, 100000),
            'progress' => fake()->numberBetween(0, 100),
            'created_by' => 1,
        ];
    }
}

