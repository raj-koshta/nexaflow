<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;
class TicketReplyFactory extends Factory {
    public function definition(): array {
        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => 1, // Optional, can be null if customer
            'message' => fake()->paragraphs(2, true),
            'is_internal' => fake()->boolean(20),
        ];
    }
}
