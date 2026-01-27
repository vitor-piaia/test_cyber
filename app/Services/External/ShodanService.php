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
}
