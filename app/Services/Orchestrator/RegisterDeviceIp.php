<?php

namespace App\Services\Orchestrator;

use App\Exceptions\Device\IpAlreadyExistsException;
use App\Exceptions\Device\NotFoundException;
use App\Services\DeviceNetworkAccessService;
use App\Services\DeviceService;
use App\Services\NetworkService;

class RegisterDeviceIp
{
    public function __construct(
        protected DeviceService $deviceService,
        protected NetworkService $networkService,
        protected DeviceNetworkAccessService $deviceNetworkAccessService
    ) {}

    public function execute(int $deviceId, string $ip): bool
    {
        $deviceExists = $this->deviceService->checkExists($deviceId);

        if (! $deviceExists) {
            throw new NotFoundException();
        }

        $checkNonExistsIp = $this->deviceNetworkAccessService->checkNonExistsIp($ip, $deviceId);

        if ($checkNonExistsIp) {
            throw new IpAlreadyExistsException();
        }

        $network = $this->networkService->findNetworkByIp($ip);

        $this->deviceNetworkAccessService->store([
            'device_id' => $deviceId,
            'ip' => $ip,
            'network_id' => $network?->id,
            'accessed_at' => now()
        ]);

        return true;
    }
}
