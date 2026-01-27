<?php

namespace App\Repositories;

use App\Models\Device;
use App\Repositories\Interfaces\DeviceRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function checkDeviceWasDeleted(string $mac): ?Device
    {
        return $this->model->where('mac', $mac)->onlyTrashed()->first();
    }

    public function deleteWithRelations(Device $device): bool
    {
        DB::transaction(function () use ($device) {
            $device->delete();
            $device->accesses()->delete();
        });

        return true;
    }

    public function restoreWithRelations(Device $device): bool
    {
        DB::transaction(function () use ($device) {
            $device->restore();
            $device->accesses()->restore();
        });

        return true;
    }
}
