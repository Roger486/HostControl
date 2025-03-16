<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationLog>
 */
class ReservationLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Looks for a random andmin user to asign its id, if none is found, create one
            'user_id' => User::where('role', 'admin')->inRandomOrder()->first()?->id
                ?? User::factory()->create(['role' => 'admin'])->id,
            //Looks for a random reservation to asign its id, if none is found, create one
            'reservation_id' => Reservation::inRandomOrder()->first()?->id ?? Reservation::factory()->create()->id,
            'action_type' => fake()->randomElement(['created', 'updated', 'cancelled', 'checked_in', 'checked_out']),
            'comments' => fake()->text()
        ];
    }
}
