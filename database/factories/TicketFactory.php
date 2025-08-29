<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];
        return [
            'subject'     => $this->faker->sentence(5),
            'body'        => $this->faker->paragraph(3),
            'note'       => $this->faker->sentences(rand(1, 3), true),
            'status'      => $this->faker->randomElement($statuses),
            'category_id' => rand(1, 5)
        ];
    }
}
