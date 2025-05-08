<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Dates setup
        $scheduledAt = $this->faker->optional()->dateTimeBetween('+1 day', '+4 months');

        $availableUntil = $this->faker->optional()->dateTimeBetween('now', $scheduledAt ?? '+5 months');

        $endsAt = $this->faker->optional()->dateTimeBetween($scheduledAt ?? '+2 days', '+6 months');

        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(500, 5000), // in cents
            'daily_price' => $this->faker->numberBetween(100, 2000),  // in cents
            'available_slots' => $this->faker->numberBetween(1, 30),
            'comments' => $this->faker->optional()->sentence(),
            // dates
            'scheduled_at' => $scheduledAt,
            'available_until' => $availableUntil?->format('Y-m-d'),
            'ends_at' => $endsAt,
        ];
    }
}
