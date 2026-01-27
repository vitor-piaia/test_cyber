<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Repositories\DeviceRepository;
use App\Services\DeviceService;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Mockery;
use PHPUnit\Framework\TestCase;

class DeviceServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    public function test_it_show_device_without_cache()
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $expectedData = [
            'id' => $deviceId,
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active'
        ];

        $deviceMock = Mockery::mock(Device::class)->makePartial()->shouldIgnoreMissing();
        $deviceMock->setRawAttributes($expectedData);

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('findDevice')
            ->once()
            ->with($deviceId)
            ->andReturn($deviceMock);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) {
                return $closure();
            });

        $deviceService = new DeviceService($deviceRepositoryMock);
        $device = $deviceService->show($deviceId);

        $this->assertEquals($expectedData, $device->toArray());
    }

    public function test_it_show_device_with_cache()
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $expectedData = [
            'id' => $deviceId,
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active'
        ];

        $deviceMock = Mockery::mock(Device::class)->makePartial()->shouldIgnoreMissing();
        $deviceMock->setRawAttributes($expectedData);
        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) use ($deviceMock) {
                return $deviceMock;
            });

        $deviceService = new DeviceService($deviceRepositoryMock);
        $device = $deviceService->show($deviceId);

        $this->assertEquals($expectedData, $device->toArray());
    }

    public function test_it_show_device_exception()
    {
        $this->expectException(Exception::class);
        $deviceId = fake()->numberBetween(1, 1000);
        $deviceMock = new Device([]);

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('findDevice')
            ->once()
            ->with($deviceId)
            ->andReturn($deviceMock);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) {
                return $closure();
            });

        $deviceService = new DeviceService($deviceRepositoryMock);
        $deviceService->show($deviceId);
    }

    public function test_it_store_device()
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $data = [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active'
        ];

        $expectedData = array_merge($data, ['id' => $deviceId]);
        $deviceMock = Mockery::mock(Device::class)->makePartial()->shouldIgnoreMissing();
        $deviceMock->setRawAttributes($expectedData);

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($deviceMock);

        $deviceService = new DeviceService($deviceRepositoryMock);
        $device = $deviceService->store($data);

        $this->assertEquals($expectedData, $device->toArray());
    }

    public function test_it_store_device_exception()
    {
        $this->expectException(Exception::class);
        $data = [
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active'
        ];

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception());

        $deviceService = new DeviceService($deviceRepositoryMock);
        $deviceService->store($data);
    }

    public function test_it_update_device()
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $data = [
            'id' => $deviceId,
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active'
        ];

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($data, $deviceId)
            ->andReturnTrue();

        Cache::shouldReceive('forget')->once();

        $deviceService = new DeviceService($deviceRepositoryMock);
        $response = $deviceService->update($deviceId, $data);

        $this->assertTrue($response);
    }

    public function test_it_update_device_exception()
    {
        $this->expectException(Exception::class);
        $deviceId = fake()->numberBetween(1, 1000);
        $data = [
            'id' => $deviceId,
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active'
        ];

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($data, $deviceId)
            ->andReturnFalse();

        $deviceService = new DeviceService($deviceRepositoryMock);
        $deviceService->update($deviceId, $data);
    }

    public function test_it_delete_device()
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $data = [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active'
        ];

        $expectedData = array_merge($data, ['id' => $deviceId]);
        $deviceMock = Mockery::mock(Device::class)->makePartial()->shouldIgnoreMissing();
        $deviceMock->setRawAttributes($expectedData);

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('findDevice')
            ->once()
            ->with($deviceId)
            ->andReturn($deviceMock);

        $deviceRepositoryMock->shouldReceive('deleteWithRelations')
            ->once()
            ->andReturn(true);

        Cache::shouldReceive('forget')->once();

        $deviceService = new DeviceService($deviceRepositoryMock);
        $response = $deviceService->delete($deviceId);

        $this->assertTrue($response);
    }

    public function test_it_delete_device_exception()
    {
        $this->expectException(Exception::class);
        $deviceId = fake()->numberBetween(1, 1000);
        $deviceMock = new Device([]);

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('findDevice')
            ->once()
            ->with($deviceId)
            ->andReturn($deviceMock);

        $deviceService = new DeviceService($deviceRepositoryMock);
        $deviceService->delete($deviceId);
    }

    public function test_it_check_exists()
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('checkExists')
            ->once()
            ->with($deviceId)
            ->andReturnTrue();

        $deviceService = new DeviceService($deviceRepositoryMock);
        $response = $deviceService->checkExists($deviceId);

        $this->assertTrue($response);
    }

    public function test_it_check_device_was_deleted_and_restore()
    {
        $deviceId = fake()->numberBetween(1, 1000);
        $data = [
            'id' => $deviceId,
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'mac' => fake()->macAddress(),
            'device_type' => 'laptop',
            'os' => 'linux',
            'status' => 'active',
            'deleted_at' => now()
        ];

        $expectedData = array_merge($data, ['id' => $deviceId]);
        $deviceMock = Mockery::mock(Device::class)->makePartial()->shouldIgnoreMissing();
        $deviceMock->setRawAttributes($expectedData);

        $deviceRepositoryMock = Mockery::mock(DeviceRepository::class);
        $deviceRepositoryMock
            ->shouldReceive('checkDeviceWasDeleted')
            ->once()
            ->with($deviceId)
            ->andReturn($deviceMock);

        $deviceRepositoryMock->shouldReceive('restoreWithRelations')
            ->once()
            ->andReturn(true);

        Cache::shouldReceive('forget')->once();

        $deviceService = new DeviceService($deviceRepositoryMock);
        $response = $deviceService->checkDeviceWasDeletedAndRestore($deviceId);

        $this->assertTrue($response);
    }
}
