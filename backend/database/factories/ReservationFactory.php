<?php

namespace Database\Factories;

use App\Models\Accommodation\Accommodation;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booked_by_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'guest_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'accommodation_id' => Accommodation::inRandomOrder()->first()
                ?->id ?? Accommodation::factory()->create()->id,
            'check_in_date' => fake()->dateTimeBetween('now', '+1 year'),
            'check_out_date' => fake()->dateTimeBetween('+2 days', '+1 year +10 days'),
            'status' => fake()->randomElement(Reservation::STATUSES),
            'comments' => fake()->optional()->text(100),
        ];
    }
}
