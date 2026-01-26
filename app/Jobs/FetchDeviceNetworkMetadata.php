<?php

namespace App\Jobs;

use App\Services\DeviceNetworkMetadataService;
use App\Services\External\ShodanService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\RateLimiter;

class FetchDeviceNetworkMetadata implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(
        public int $deviceNetworkAccessId,
        public string $ip
    ) {}

    public function uniqueId(): string
    {
        return $this->deviceNetworkAccessId.'-'.$this->ip;
    }

    public function uniqueFor(): int
    {
        return 300;
    }

    public function handle(ShodanService $shodanService, DeviceNetworkMetadataService $deviceNetworkMetadataService)
    {
        if (! RateLimiter::attempt('shodan-api', 2, fn () => true)) {
            $this->release(60);
            return;
        }

        $metadata = $shodanService->hostInfo($this->ip);
        $deviceNetworkMetadataService->store($this->deviceNetworkAccessId, $metadata);
    }

    public function backoff(): array
    {
        return [30, 120, 300];
    }

    public function tries(): int
    {
        return 3;
    }
}
