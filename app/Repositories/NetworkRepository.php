<?php

namespace App\Repositories;

use App\Models\Network;
use App\Repositories\Interfaces\NetworkRepositoryInterface;

class NetworkRepository extends BaseRepository implements NetworkRepositoryInterface
{
    public function model()
    {
        return Network::class;
    }

    public function listPaginate(int $page, string $orderBy, int $perPage)
    {
        return $this->model->orderBy('created_at', $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findNetwork(int $networkId): ?Network
    {
        return $this->model->where('id', $networkId)->first();
    }

    public function findNetworkByIp(string $ip): ?Network
    {
        return $this->model->whereRaw('? <<= cidr', [$ip])->first();
    }
}
