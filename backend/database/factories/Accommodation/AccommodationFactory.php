<?php

namespace Database\Factories\Accommodation;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accommodation\Accommodation>
 */
class AccommodationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'accommodation_code' => fake()->unique()->bothify('??##'),
            'section' => fake()->word(),
            'capacity' => random_int(1, 10),
            'price_per_day' => random_int(1000, 50000),
            'is_available' => fake()->boolean(),
            'comments' => fake()->text(100)
        ];
    }
}
