<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NetworkFactory extends Factory
{
//    protected static ?string $password;

    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1, 1000),
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => fake()->ipv4() . '/24',
            'location' => fake()->city(),
            'status' => 'active'
//            'password' => static::$password ??= Hash::make('password'),
        ];
    }

//    /**
//     * Indicate that the model's email address should be unverified.
//     */
//    public function unverified(): static
//    {
//        return $this->state(fn (array $attributes) => [
//            'email_verified_at' => null,
//        ]);
//    }
}
