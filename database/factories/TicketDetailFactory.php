<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketDetail>
 */
class TicketDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'description' => fake()->paragraph(),
            'summary' => fake()->sentence(),
            'keywords' => fake()->words(5),
            'sentiment_score' => fake()->randomFloat(2, -1, 1),
            'processed_at' => now()->toIso8601String(),
        ];
    }
}
