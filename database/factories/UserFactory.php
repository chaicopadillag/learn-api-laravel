<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
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
        $roles  = ['student', 'teacher'];
        $idCard = str_pad(fake()->numberBetween(0, 99999999999), 11, '0', STR_PAD_LEFT);

        return [
            'name'              => fake()->firstName(),
            'lastname'          => fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'age'               => fake()->numberBetween(18, 60),
            'id_card'           => $idCard,
            'email_verified_at' => now(),
            'password'          => Hash::make(env('ADMIN_PASSWORD', '123456')),
            'remember_token'    => Str::random(10),
            'role'              => fake()->randomElement($roles),
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
