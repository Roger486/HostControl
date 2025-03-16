<?php

namespace Database\Factories\Accommodation;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accommodation\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bed_amount' => fake()->numberBetween(1, 6),
            'has_air_conditioning' => fake()->boolean(),
            'has_private_wc' => fake()->boolean()
        ];
    }
}
