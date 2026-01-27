<?php

namespace App\Repositories\Interfaces;

use App\Models\Device;

interface DeviceRepositoryInterface
{
    public function listPaginate(int $page, string $orderBy, int $perPage);

    public function findDevice(int $deviceId);

    public function create(array $data);

    public function update(array $data, int $deviceId);

    public function delete(int $deviceId);

    public function checkExists(int $deviceId);

    public function checkDeviceWasDeleted(string $mac);

    public function deleteWithRelations(Device $device);
    public function restoreWithRelations(Device $device);
}
