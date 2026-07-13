<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class LeadFactory extends Factory {
    public function definition(): array {
        return [
            'lead_code' => 'LED-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company' => fake()->company(),
            'source' => fake()->randomElement(['website', 'referral', 'cold_call', 'trade_show']),
            'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'lost']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'expected_value' => fake()->randomFloat(2, 1000, 50000),
            'remarks' => fake()->sentence(),
            'assigned_to' => 1, // Will be overridden in seeder
            'created_by' => 1,
        ];
    }
}

