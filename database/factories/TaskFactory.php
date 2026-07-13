<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
class TaskFactory extends Factory {
    public function definition(): array {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['Todo', 'In Progress', 'Review', 'Done']),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High', 'Urgent']),
            'due_date' => fake()->dateTimeBetween('-1 week', '+3 weeks'),
            'assigned_to' => 1,
            'created_by' => 1,
        ];
    }
}
