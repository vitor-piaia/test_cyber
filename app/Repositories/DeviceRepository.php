<?php

namespace App\Repositories;

use App\Models\Device;
use App\Repositories\Interfaces\DeviceRepositoryInterface;

class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{
//    public function getFieldsSearchable()
//    {
//        return [
//            'destiny',
//            'departure_date',
//            'return_date',
//            'status',
//        ];
//    }

    public function model()
    {
        return Device::class;
    }

    public function listPaginate(int $page, string $orderBy, int $perPage)
    {
        return $this->model->orderBy('created_at', $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findDevice(int $networkId): ?Network
    {
        return $this->model->where('id', $networkId)->first();
    }
}
