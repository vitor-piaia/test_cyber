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
}
