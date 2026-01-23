<?php

namespace App\Repositories;

use App\Models\Network;
use App\Repositories\Interfaces\NetworkRepositoryInterface;

class NetworkRepository extends BaseRepository implements NetworkRepositoryInterface
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
//
//    public function checkOrderIdExists(int $orderId): bool
//    {
//        return $this->model
//            ->where('order_id', $orderId)
//            ->exists();
//    }
//
//    public function checkOrderIsApproved(int $orderId): bool
//    {
//        return $this->model
//            ->where('order_id', $orderId)
//            ->where('status', OrderEnum::STATUS_APPROVED)
//            ->where('departure_date', '>=', now())
//            ->exists();
//    }
}
