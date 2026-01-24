<?php

namespace App\Repositories\Interfaces;

interface DeviceNetworkAccessRepositoryInterface
{
    public function create(array $data);

    public function checkNonExistsIp(string $ip, int $deviceId);
}
