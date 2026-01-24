<?php

namespace App\Services\External;

use App\Integrations\Shodan\Interfaces\ShodanClientInterface;

class ShodanService
{
    public function __construct(private ShodanClientInterface $client) {}

    public function hostInfo(string $ip): array
    {
        return $this->client->get("/shodan/host/{$ip}");
    }

    public function search(string $query, int $page = 1): array
    {
        return $this->client->get('/shodan/host/search', [
            'query' => $query,
            'page'  => $page,
        ]);
    }
}
