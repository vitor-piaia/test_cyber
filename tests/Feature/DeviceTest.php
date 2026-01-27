<?php

namespace Feature;

use App\Jobs\FetchDeviceNetworkMetadata;
use App\Models\Device;
use App\Models\Network;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_show(): void
    {
        $device = Device::factory()->create();
        $response = $this->getJson(route('device.show', ['deviceId' => $device->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'mac',
                    'device_type',
                    'os',
                    'status',
                    'accesses'
                ]
            ]);
    }

    public function test_it_show_with_invalid_id(): void
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $response = $this->getJson(route('device.show', ['deviceId' => $deviceId]));

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJsonStructure(['message']);
    }

    public function test_it_store(): void
    {
        Cache::flush();
        Bus::fake();

        $ip = fake()->ipv4();
        Network::factory()->create(['cidr' => $ip]);
        $data = $this->data();
        $data['ip'] = $ip;
        $response = $this->postJson(
            route('device.store'),
            $data
        );

        Bus::assertDispatched(FetchDeviceNetworkMetadata::class, function ($job) {
            return $job->queue === 'shodan';
        });

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'mac',
                    'device_type',
                    'os',
                    'status',
                    'accesses'
                ]
            ]);
    }

    public function test_it_store_with_invalid_ip(): void
    {
        $data = $this->data();
        $data['ip'] = null;
        $response = $this->postJson(
            route('device.store'),
            $data
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message']);
    }

    public function test_it_update(): void
    {
        $device = Device::factory()->create();
        $response = $this->putJson(
            route('device.update', ['deviceId' => $device->id]),
            $this->data()
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message']);
    }

    public function test_it_update_with_invalid_id(): void
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $response = $this->putJson(
            route('device.update', ['deviceId' => $deviceId]),
            $this->data()
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJsonStructure(['message']);
    }

    public function test_it_update_with_invalid_name(): void
    {
        $device = Device::factory()->create();
        $data = $this->data();
        $data['name'] = null;
        $response = $this->putJson(
            route('device.update', ['deviceId' => $device->id]),
            $data
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message']);
    }

    public function test_it_delete(): void
    {
        $device = Device::factory()->create();
        $response = $this->deleteJson(route('device.delete', ['deviceId' => $device->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message']);
    }

    public function test_it_delete_with_invalid_id(): void
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $response = $this->deleteJson(route('device.delete', ['deviceId' => $deviceId]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    private function data(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => fake()->text(15),
            'os' => fake()->text(10),
            'status' => 'active'
        ];
    }
}
