<?php

namespace Feature;

use App\Jobs\FetchDeviceNetworkMetadata;
use App\Models\Device;
use App\Models\DeviceNetworkAccess;
use App\Models\Network;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeviceIpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_store_ip(): void
    {
        Cache::flush();
        Bus::fake();

        $ip = fake()->ipv4();
        Network::factory()->create(['cidr' => $ip]);
        $device = Device::factory()->create();

        $response = $this->postJson(
            route('device.ip.store', ['deviceId' => $device->id]),
            ['ip' => $ip]
        );

        Bus::assertDispatched(FetchDeviceNetworkMetadata::class, function ($job) {
            return $job->queue === 'shodan';
        });

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['message']);
    }

    public function test_it_delete_with_invalid_id(): void
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $response = $this->postJson(
            route('device.ip.store', ['deviceId' => $deviceId]),
            ['ip' => fake()->ipv4()]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    public function test_it_store_ip_already_exists(): void
    {
        $ip = fake()->ipv4();
        $network = Network::factory()->create(['cidr' => $ip]);
        $device = Device::factory()->create();
        DeviceNetworkAccess::factory()->create([
            'device_id' => $device->id,
            'network_id' => $network->id,
            'ip' => $ip
        ]);

        $response = $this->postJson(
            route('device.ip.store', ['deviceId' => $device->id]),
            ['ip' => $ip]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message']);
    }
}
