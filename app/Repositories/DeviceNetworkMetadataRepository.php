<?php

namespace App\Repositories;

use App\Models\DeviceNetworkMetadata;
use App\Repositories\Interfaces\DeviceNetworkMetadataRepositoryInterface;

class DeviceNetworkMetadataRepository extends BaseRepository implements DeviceNetworkMetadataRepositoryInterface
{
    public function model()
    {
        return DeviceNetworkMetadata::class;
    }
}
