<?php

namespace Database\Factories;

use App\Models\Accommodation\Accommodation;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
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
        $checkIn = Carbon::instance(fake()->dateTimeBetween('now', '+1 year'));

        $checkOut = $checkIn->copy()->addDays(fake()->numberBetween(1, 14));

        return [
            'booked_by_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'guest_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'accommodation_id' => Accommodation::inRandomOrder()->first()
                ?->id ?? Accommodation::factory()->create()->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'status' => fake()->randomElement(Reservation::STATUSES),
            'comments' => fake()->optional()->text(100),
        ];
    }
}
