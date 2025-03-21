<?php

namespace Database\Factories;

use App\Models\Companion;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Companion>
 */
class CompanionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::inRandomOrder()->first()?->id ?? Reservation::factory()->create()->id,
            'document_number' => fake()->optional()->bothify('########??'),
            'document_type' => fake()->randomElement(Companion::DOCUMENT_TYPES),
            'first_name' => fake()->firstName(),
            'last_name_1' => fake()->lastName(),
            'last_name_2' => fake()->optional()->lastName(),
            'birthdate' => fake()->date(),
            'comments' => fake()->text(100)
        ];
    }
}
