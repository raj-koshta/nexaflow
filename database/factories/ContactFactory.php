<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
class ContactFactory extends Factory {
    public function definition(): array {
        return [
            'client_id' => Client::factory(),
            'name' => fake()->name(),
            'designation' => fake()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'department' => fake()->word(),
            'is_primary' => fake()->boolean(20),
            'created_by' => 1,
        ];
    }
}
