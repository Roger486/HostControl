<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationLog;
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
            'user_id' => User::where('role', User::ROLE_ADMIN)->inRandomOrder()->first()?->id
                ?? User::factory()->create(['role' => User::ROLE_ADMIN])->id,
            //Looks for a random reservation to asign its id, if none is found, create one
            'reservation_id' => Reservation::inRandomOrder()->first()?->id ?? Reservation::factory()->create()->id,
            'action_type' => fake()->randomElement(ReservationLog::ACTIONS),
            'log_detail' => fake()->text()
        ];
    }
}
