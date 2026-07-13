<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class AiPromptTemplateFactory extends Factory {
    public function definition(): array {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'system_prompt' => fake()->paragraph(),
            'user_prompt' => fake()->paragraph(),
            'is_active' => fake()->boolean(80),
        ];
    }
}
