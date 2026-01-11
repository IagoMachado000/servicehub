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
            'processed_data' => null,
        ];
    }

    /**
     * Estado para simular que o Job já processou o anexo.
     */
    public function processed(): static
    {
        return $this->state(fn(array $attributes) => [
            'processed_data' => [
                // Aqui é definida a estrutura JSON que o Job geraria
                'summary' => fake()->sentence(),
                'keywords' => fake()->words(5),
                'sentiment_score' => fake()->randomFloat(2, 0, 1),
                'processed_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
