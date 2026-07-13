<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory {
    public function definition(): array {
        return [
            'client_code' => 'CLI-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'company_name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'industry' => fake()->randomElement(['Tech', 'Finance', 'Health']),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'created_by' => 1,
        ];
    }
}

