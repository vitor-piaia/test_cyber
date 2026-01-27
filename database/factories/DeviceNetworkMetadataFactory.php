<?php

namespace Database\Factories;

use App\Models\DeviceNetworkAccess;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceNetworkMetadataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1, 1000),
            'device_network_access_id' => DeviceNetworkAccess::factory(),
            'isp' => fake()->text(20),
            'domains' => json_encode([]),
            'hostnames' => json_encode([]),
            'geolocation' => json_encode([]),
            'ports' => json_encode([]),
            'last_shodan_scan_at' => now()
        ];
    }
}
