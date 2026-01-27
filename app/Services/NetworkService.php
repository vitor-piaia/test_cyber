<?php

namespace App\Services;

use App\Exceptions\Network\NotFoundException;
use App\Models\Network;
use App\Repositories\Interfaces\NetworkRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class NetworkService
{
    public function __construct(protected NetworkRepositoryInterface $networkRepository) {}

    public function list(int $page = 1, string $orderBy = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        return $this->networkRepository->listPaginate($page, $orderBy, $perPage);
    }

    public function show(int $networkId): ?Model
    {
        $network = Cache::remember("network:$networkId", 600, function () use ($networkId) {
            return $this->networkRepository->findNetwork($networkId);
        });

        if (! $network->id) {
            throw new Exception;
        }

        return $network;
    }

    public function store(array $data): Model
    {
        $data['cidr'] = $this->normalizeCidr($data['cidr']);
        $network = $this->networkRepository->create($data);

        if (! $network->id) {
            throw new Exception;
        }

        return $network;
    }

    public function update(int $networkId, array $data): bool
    {
        $data['cidr'] = $this->normalizeCidr($data['cidr']);
        $update = $this->networkRepository->update($data, $networkId);

        if (! $update) {
            throw new Exception;
        }

        Cache::forget("network:{$networkId}");

        return true;
    }

    public function delete($networkId): bool
    {
        $network = $this->networkRepository->findNetwork($networkId);

        if (empty($network)) {
            throw new NotFoundException();
        }

        $this->networkRepository->deleteWithRelations($network);

        Cache::forget("network:{$networkId}");

        return true;
    }

    public function findNetworkByIp(string $ip): ?Network
    {
        return $this->networkRepository->findNetworkByIp($ip);
    }

    private function normalizeCidr(string $cidr): string
    {
        [$ip, $prefix] = explode('/', $cidr);

        $ipLong = ip2long($ip);
        $mask = -1 << (32 - $prefix);

        $network = long2ip($ipLong & $mask);

        return "{$network}/{$prefix}";
    }
}
