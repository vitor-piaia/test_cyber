<?php

namespace App\Jobs;

use App\Services\DeviceNetworkMetadataService;
use App\Services\External\ShodanService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchDeviceNetworkMetadata implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $deviceNetworkAccessId,
        public string $ip
    ) {}

    public function handle(ShodanService $shodanService, DeviceNetworkMetadataService $deviceNetworkMetadataService)
    {
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
