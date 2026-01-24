<?php

namespace App\Repositories;


use App\Models\DeviceNetworkAccess;
use App\Repositories\Interfaces\DeviceNetworkAccessRepositoryInterface;

class DeviceNetworkAccessRepository extends BaseRepository implements DeviceNetworkAccessRepositoryInterface
{
    public function model()
    {
        return DeviceNetworkAccess::class;
    }

    public function checkNonExistsIp(string $ip, int $deviceId): bool
    {
        return $this->model->where([
            'ip' => $ip,
            'device_id' => $deviceId
        ])->exists();
    }
}
