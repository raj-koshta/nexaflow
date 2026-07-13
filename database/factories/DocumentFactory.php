<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class DocumentFactory extends Factory {
    public function definition(): array {
        return [
            'file_name' => fake()->words(3, true) . '.pdf',
            'file_path' => 'documents/' . fake()->word() . '.pdf',
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(1024, 10485760), // 1KB to 10MB
            'created_by' => 1,
        ];
    }
}
