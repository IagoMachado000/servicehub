<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'status' => fake()->randomElement(['open', 'processing', 'done', 'closed']),
            'attachment_path' => null,
        ];
    }

    /**
     * Estado para simular um ticket que tem anexo.
     */
    public function withAttachment(): static
    {
        return $this->state(fn(array $attributes) => [
            // Simula um caminho de arquivo falso
            'attachment_path' => 'tickets/attachments/' . fake()->uuid() . '.json',
        ]);
    }
}
