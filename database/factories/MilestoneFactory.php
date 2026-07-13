<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
class MilestoneFactory extends Factory {
    public function definition(): array {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'due_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'status' => fake()->randomElement(['pending', 'completed']),
            'progress' => fake()->numberBetween(0, 100),
        ];
    }
}
