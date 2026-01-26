<?php

namespace App\Repositories;

use App\Models\Device;
use App\Repositories\Interfaces\DeviceRepositoryInterface;

class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{
    public function model()
    {
        return Device::class;
    }

    public function listPaginate(int $page, string $orderBy, int $perPage)
    {
        return $this->model->orderBy('created_at', $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findDevice(int $deviceId): ?Device
    {
        return $this->model->with(['accesses.metadata'])
            ->where('id', $deviceId)
            ->first();
    }

    public function checkExists(int $deviceId): bool
    {
        return $this->model->where('id', $deviceId)->exists();
    }
}
