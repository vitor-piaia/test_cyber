<?php

namespace Tests\Feature;

use App\Jobs\FetchDeviceNetworkMetadata;
use App\Models\Device;
use App\Models\DeviceNetworkAccess;
use App\Models\DeviceNetworkMetadata;
use App\Models\Network;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeviceNetworkAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_refresh_metadata(): void
    {
        Cache::flush();
        Bus::fake();
        $device = Device::factory()->create();
        $network = Network::factory()->create();
        $access = DeviceNetworkAccess::factory()->create(['device_id' => $device->id, 'network_id' => $network->id]);
        DeviceNetworkMetadata::factory()->create(['device_network_access_id' => $access->id]);

        $response = $this->postJson(
            route('device.network.access.refresh.metadata', ['access' => $access->id]),
            $this->data()
        );

        Bus::assertDispatched(FetchDeviceNetworkMetadata::class, function ($job) {
            return $job->queue === 'shodan';
        });

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function test_it_refresh_metadata_exception(): void
    {
        $accessId = fake()->numberBetween(1, 1000);

        $response = $this->postJson(
            route('device.network.access.refresh.metadata', ['access' => $accessId]),
            $this->data()
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    private function data(): array
    {
        return [
            'isp' => fake()->text(20),
            'domains' => json_encode([]),
            'hostnames' => json_encode([]),
            'geolocation' => json_encode([]),
            'ports' => json_encode([]),
            'last_shodan_scan_at' => now()
        ];
    }
}
