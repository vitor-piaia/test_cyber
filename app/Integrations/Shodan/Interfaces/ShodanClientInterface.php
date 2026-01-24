<?php

namespace App\Integrations\Shodan\Interfaces;

interface ShodanClientInterface
{
    public function get(string $uri, array $query = []): array;
    public function post(string $uri, array $body = []): array;
}
