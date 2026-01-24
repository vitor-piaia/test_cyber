<?php

namespace App\Repositories\Interfaces;

interface NetworkRepositoryInterface
{
    public function listPaginate(int $page, string $orderBy, int $perPage);
    public function findNetwork(int $networkId);
    public function create(array $data);
    public function update(array $data, int $networkId);
    public function delete(int $networkId);
    public function findNetworkByIp(string $ip);
}
