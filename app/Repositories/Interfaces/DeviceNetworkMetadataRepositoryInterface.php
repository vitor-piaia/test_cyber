<?php

namespace App\Repositories\Interfaces;

interface DeviceNetworkMetadataRepositoryInterface
{
    public function updateOrCreate(array $deviceNetworkAccessId, array $data);
}
