<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
class TicketFactory extends Factory {
    public function definition(): array {
        return [
            'ticket_number' => 'TKT-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'subject' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'client_id' => Client::factory(),
            'category' => fake()->randomElement(['billing', 'technical', 'sales', 'general']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'assigned_to' => 1,
            'created_by' => 1,
        ];
    }
}
