<?php

namespace App\Services;

use App\Repositories\Interfaces\DeviceNetworkMetadataRepositoryInterface;

class DeviceNetworkMetadataService
{
    public function __construct(
        protected DeviceNetworkMetadataRepositoryInterface $deviceNetworkMetadataRepository,
    ) {}

    public function store(int $deviceNetworkAccessId, array $metadata): void
    {
        $data = [
            'device_network_access_id' => $deviceNetworkAccessId,
            'isp' => $metadata['isp'],
            'domains' => json_encode($metadata['domains']),
            'hostnames' => json_encode($metadata['hostnames']),
            'geolocation' => json_encode(['latitude' => $metadata['latitude'], 'longitude' => $metadata['longitude']]),
            'ports' => json_encode($metadata['ports']),
            'last_shodan_scan_at' => now(),
        ];

        $this->deviceNetworkMetadataRepository->create($data);
    }
}
