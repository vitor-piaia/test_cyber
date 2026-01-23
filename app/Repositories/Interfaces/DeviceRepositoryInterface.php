<?php

namespace App\Repositories\Interfaces;

interface DeviceRepositoryInterface
{
    public function listPaginate(int $page, string $orderBy, int $perPage);

    public function findDevice(int $deviceId);

    public function create(array $data);

    public function update(array $data, int $deviceId);

    public function delete(int $deviceId);
}
