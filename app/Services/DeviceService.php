<?php

namespace App\Services;

use App\Exceptions\Device\NotFoundException;
use App\Repositories\Interfaces\DeviceRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class DeviceService
{
    public function __construct(protected DeviceRepositoryInterface $deviceRepository, protected DeviceNetworkAccessService $deviceNetworkAccessService) {}

    public function list(int $page = 1, string $orderBy = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        return $this->deviceRepository->listPaginate($page, $orderBy, $perPage);
    }

    public function show(int $deviceId): ?Model
    {
        $device = $this->deviceRepository->findDevice($deviceId);

        if (! $device->id) {
            throw new Exception;
        }
        $ok = $this->deviceNetworkAccessService->store(['device_id' => $device->id, 'ip' => '8.8.8.8']);
        echo'<pre>';print_r($ok);exit('teste');

        return $device;
    }

    public function store(array $data): Model
    {
        $device = $this->deviceRepository->create($data);

        if (! $device->id) {
            throw new Exception;
        }

        return $device;
    }

    public function update(int $deviceId, array $data): bool
    {
        $update = $this->deviceRepository->update($data, $deviceId);

        if (! $update) {
            throw new Exception;
        }

        return true;
    }

    public function delete($deviceId): bool
    {
        $network = $this->deviceRepository->findDevice($deviceId);

        if (empty($network)) {
            throw new NotFoundException();
        }

        $delete = $this->deviceRepository->delete($deviceId);

        if (! $delete) {
            throw new Exception;
        }

        return true;
    }
}
