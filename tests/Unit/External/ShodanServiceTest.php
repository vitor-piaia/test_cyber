<?php

namespace Tests\Unit\External;

use App\Integrations\Shodan\ShodanClient;
use App\Services\External\ShodanService;
use Mockery;
use PHPUnit\Framework\TestCase;

class ShodanServiceTest extends TestCase
{
    public function test_fetch_metadata_from_shodan(): void
    {
        $ip = fake()->ipv4();
        $shodanClientMock = Mockery::mock(ShodanClient::class);
        $shodanClientMock
            ->shouldReceive('get')
            ->once()
            ->with("/shodan/host/{$ip}")
            ->andReturn([]);

        $service = new ShodanService($shodanClientMock);
        $response = $service->hostInfo($ip);

        $this->assertIsArray($response);
    }
}
