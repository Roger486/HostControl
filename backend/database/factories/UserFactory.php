<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name_1' => fake()->lastName(),
            'last_name_2' => fake()->optional()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'birthdate' => fake()->date('Y-m-d', '-18 years'),
            'address' => fake()->address(),
            'document_type' => fake()->randomElement(User::DOCUMENT_TYPES),
            'document_number' => fake()->bothify('########??'),
            'phone' => fake()->phoneNumber(),
            'role' => fake()->randomElement(User::ROLES),
            'comments' => fake()->text(100),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // Not in use for now, no email verification implemented
    /*
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    */
}
