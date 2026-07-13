<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class CompanyFactory extends Factory {
    public function definition(): array {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'address' => fake()->address(),
        ];
    }
}
