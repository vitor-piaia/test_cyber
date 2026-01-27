<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{

    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1, 1000),
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => fake()->text(15),
            'os' => fake()->text(10),
            'status' => 'active'
        ];
    }
}
