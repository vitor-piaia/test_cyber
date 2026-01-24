<?php

namespace App\Services\Orchestrator;

use App\Services\DeviceNetworkAccessService;
use App\Services\DeviceService;
use App\Services\NetworkService;
use Exception;
use Illuminate\Database\Eloquent\Model;

class RegisterDeviceWithAccess
{
    public function __construct(
        protected DeviceService $deviceService,
        protected NetworkService $networkService,
        protected DeviceNetworkAccessService $deviceNetworkAccessService
    ) {}

    public function execute(array $post): Model
    {
        $dataDevice = [
            'name' => $post['name'],
            'description' => $post['description'],
            'mac' => $post['mac'],
            'device_type' => $post['device_type'],
            'os' => $post['os'],
            'status' => $post['status']
        ];

        $device = $this->deviceService->store($dataDevice);

        if (! empty($post['ip'])) {
            $ip = $post['ip'];
            $network = $this->networkService->findNetworkByIp($ip);

            $this->deviceNetworkAccessService->store([
                'device_id' => $device->id,
                'ip' => $ip,
                'network_id' => $network?->id,
                'accessed_at' => now()
            ]);
        }

        return $device;
    }
}
