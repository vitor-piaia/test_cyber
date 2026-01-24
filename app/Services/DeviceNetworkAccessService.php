<?php

namespace App\Services;

use App\Repositories\Interfaces\DeviceNetworkAccessRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;

class DeviceNetworkAccessService
{
    public function __construct(
        protected DeviceNetworkAccessRepositoryInterface $deviceNetworkAccessRepository,
    ) {}

    public function store(array $data): Model
    {
        $access = $this->deviceNetworkAccessRepository->create($data);

        if (! $access->id) {
            throw new Exception;
        }

        return $access;
    }
}
