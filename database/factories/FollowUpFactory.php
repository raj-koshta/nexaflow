<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class FollowUpFactory extends Factory {
    public function definition(): array {
        return [
            'follow_date' => fake()->dateTimeBetween('-2 days', '+1 week'),
            'follow_time' => fake()->time(),
            'remarks' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'completed']),
            'created_by' => 1,
        ];
    }
}
