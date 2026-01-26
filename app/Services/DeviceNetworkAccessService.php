<?php

namespace App\Services;

use App\Jobs\FetchDeviceNetworkMetadata;
use App\Repositories\Interfaces\DeviceNetworkAccessRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;

class DeviceNetworkAccessService
{
    public function __construct(
        protected DeviceNetworkAccessRepositoryInterface $deviceNetworkAccessRepository,
    ) {}

    public function store(array $data): Model
    {
        $access = $this->deviceNetworkAccessRepository->create($data);

        if (! $access->id) {
            throw new Exception;
        }

        FetchDeviceNetworkMetadata::dispatch($access->id, $access->ip)->onQueue('shodan');

        return $access;
    }

    public function checkNonExistsIp(string $ip, int $deviceId): bool
    {
        return $this->deviceNetworkAccessRepository->checkNonExistsIp($ip, $deviceId);
    }

    public function refreshMetadata(int $accessId, string $accessIp): void
    {
        FetchDeviceNetworkMetadata::dispatch($accessId, $accessIp)->onQueue('shodan');
    }
}
