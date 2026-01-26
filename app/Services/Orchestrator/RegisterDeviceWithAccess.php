<?php

namespace App\Services\Orchestrator;

use App\Exceptions\Network\NotFoundException;
use App\Services\DeviceNetworkAccessService;
use App\Services\DeviceService;
use App\Services\NetworkService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegisterDeviceWithAccess
{
    public function __construct(
        protected DeviceService $deviceService,
        protected NetworkService $networkService,
        protected DeviceNetworkAccessService $deviceNetworkAccessService
    ) {}

    public function execute(array $post): Model
    {
        DB::beginTransaction();
        $dataDevice = [
            'name' => $post['name'],
            'description' => $post['description'],
            'mac' => $post['mac'],
            'device_type' => $post['device_type'],
            'os' => $post['os'],
            'status' => $post['status']
        ];

        $device = $this->deviceService->checkDeviceWasDeletedAndRestore($dataDevice['mac']);

        if (empty($device)) {
            $device = $this->deviceService->store($dataDevice);
        }

        $ip = $post['ip'];
        $network = $this->networkService->findNetworkByIp($ip);

        if (empty($network)) {
            DB::rollBack();
            throw new NotFoundException();
        }

        $access = $this->deviceNetworkAccessService->checkNonExistsIp($ip, $device->id);
        if (! $access) {
            $this->deviceNetworkAccessService->store([
                'device_id' => $device->id,
                'ip' => $ip,
                'network_id' => $network?->id,
                'accessed_at' => now()
            ]);
        }

        DB::Commit();
        return $device;
    }
}
