<?php

namespace Database\Factories\Accommodation;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accommodation\House>
 */
class HouseFactory extends Factory
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
            'room_amount' => fake()->numberBetween(1, 4),
            'has_air_conditioning' => fake()->boolean()
        ];
    }
}
