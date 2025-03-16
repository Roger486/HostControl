<?php

namespace Database\Factories\Accommodation;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accommodation\CampingSpot>
 */
class CampingSpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'area_size_m2' => fake()->numberBetween(10, 50),
            'has_electricity' => fake()->boolean(),
            'accepts_caravan' => fake()->boolean()
        ];
    }
}
