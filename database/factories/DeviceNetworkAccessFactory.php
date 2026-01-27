<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Network;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceNetworkAccessFactory extends Factory
{

    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1, 1000),
            'ip' => fake()->ipv4(),
            'device_id' => Device::factory(),
            'network_id' => Network::factory(),
            'accessed_at' => now(),
            'disconnected_at' => null
        ];
    }
}
