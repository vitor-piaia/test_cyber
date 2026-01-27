<?php

namespace Tests\Unit;

use App\Models\Network;
use App\Repositories\NetworkRepository;
use App\Services\NetworkService;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Mockery;
use PHPUnit\Framework\TestCase;

class NetworkServiceTest extends TestCase
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

    public function test_it_show_network_without_cache()
    {
        $networkId = fake()->numberBetween(1, 1000);
        $expectedData = [
            'id' => $networkId,
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];

        $networkMock = Mockery::mock(Network::class)->makePartial()->shouldIgnoreMissing();
        $networkMock->setRawAttributes($expectedData);

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('findNetwork')
            ->once()
            ->with($networkId)
            ->andReturn($networkMock);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) {
                return $closure();
            });

        $networkService = new NetworkService($networkRepositoryMock);
        $network = $networkService->show($networkId);

        $this->assertEquals($expectedData, $network->toArray());
    }

    public function test_it_show_network_with_cache()
    {
        $networkId = fake()->numberBetween(1, 1000);
        $expectedData = [
            'id' => $networkId,
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];

        $networkMock = Mockery::mock(Network::class)->makePartial()->shouldIgnoreMissing();
        $networkMock->setRawAttributes($expectedData);
        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) use ($networkMock) {
                return $networkMock;
            });

        $networkService = new NetworkService($networkRepositoryMock);
        $network = $networkService->show($networkId);

        $this->assertEquals($expectedData, $network->toArray());
    }

    public function test_it_show_network_exception()
    {
        $this->expectException(Exception::class);
        $networkId = fake()->numberBetween(1, 1000);
        $networkMock = new Network([]);

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('findNetwork')
            ->once()
            ->with($networkId)
            ->andReturn($networkMock);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) {
                return $closure();
            });

        $networkService = new NetworkService($networkRepositoryMock);
        $networkService->show($networkId);
    }

    public function test_it_store_network()
    {
        $networkId = fake()->numberBetween(1, 1000);
        $data = [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];

        $expectedData = array_merge($data, ['id' => $networkId]);
        $networkMock = Mockery::mock(Network::class)->makePartial()->shouldIgnoreMissing();
        $networkMock->setRawAttributes($expectedData);

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($networkMock);

        $networkService = new NetworkService($networkRepositoryMock);
        $network = $networkService->store($data);

        $this->assertEquals($expectedData, $network->toArray());
    }

    public function test_it_store_network_exception()
    {
        $this->expectException(Exception::class);
        $data = [
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception());

        $networkService = new NetworkService($networkRepositoryMock);
        $networkService->store($data);
    }

    public function test_it_update_network()
    {
        $networkId = fake()->numberBetween(1, 1000);
        $data = [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($data, $networkId)
            ->andReturnTrue();

        Cache::shouldReceive('forget')->once();

        $networkService = new NetworkService($networkRepositoryMock);
        $response = $networkService->update($networkId, $data);

        $this->assertTrue($response);
    }

    public function test_it_update_network_exception()
    {
        $this->expectException(Exception::class);
        $networkId = fake()->numberBetween(1, 1000);
        $data = [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($data, $networkId)
            ->andReturnFalse();

        $networkService = new NetworkService($networkRepositoryMock);
        $networkService->update($networkId, $data);
    }

    public function test_it_delete_network()
    {
        $networkId = fake()->numberBetween(1, 1000);
        $data = [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];

        $expectedData = array_merge($data, ['id' => $networkId]);
        $networkMock = Mockery::mock(Network::class)->makePartial()->shouldIgnoreMissing();
        $networkMock->setRawAttributes($expectedData);

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('findNetwork')
            ->once()
            ->with($networkId)
            ->andReturn($networkMock);

        $networkRepositoryMock->shouldReceive('deleteWithRelations')
            ->once()
            ->andReturn(true);

        Cache::shouldReceive('forget')->once();

        $networkService = new NetworkService($networkRepositoryMock);
        $response = $networkService->delete($networkId);

        $this->assertTrue($response);
    }

    public function test_it_delete_network_exception()
    {
        $this->expectException(Exception::class);
        $networkId = fake()->numberBetween(1, 1000);
        $networkMock = new Network([]);

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('findNetwork')
            ->once()
            ->with($networkId)
            ->andReturn($networkMock);

        $networkService = new NetworkService($networkRepositoryMock);
        $networkService->delete($networkId);
    }

    public function test_it_find_network_by_ip()
    {
        $ip = '8.8.8.8';
        $expectedData = [
            'id' => fake()->numberBetween(1, 1000),
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => '8.8.8.0/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];
        $networkMock = Mockery::mock(Network::class)->makePartial()->shouldIgnoreMissing();
        $networkMock->setRawAttributes($expectedData);

        $networkRepositoryMock = Mockery::mock(NetworkRepository::class);
        $networkRepositoryMock
            ->shouldReceive('findNetworkByIp')
            ->once()
            ->with($ip)
            ->andReturn($networkMock);

        $networkService = new NetworkService($networkRepositoryMock);
        $network = $networkService->findNetworkByIp($ip);

        $this->assertEquals($expectedData, $network->toArray());
    }
}
