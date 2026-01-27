<?php

namespace App\Integrations\Shodan;

use App\Integrations\Shodan\Interfaces\ShodanClientInterface;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Log;

class ShodanClient implements ShodanClientInterface
{
    public function __construct(
        private Factory $http,
        private string $baseUrl,
        private string $apiKey,
        private int $timeout
    ) {}

    protected function baseRequest()
    {
        return $this->http
            ->baseUrl($this->baseUrl)
            ->timeout($this->timeout);
    }

    public function get(string $uri, array $query = []): array
    {
        $response = $this->baseRequest()->get(
            $uri,
            $query + ['key' => $this->apiKey]
        );

        if ($response->failed()) {
            Log::error($response->json());
            throw new \RuntimeException('Shodan API error');
        }

        return $response->json();
    }

    public function post(string $uri, array $body = []): array
    {
        $response = $this->baseRequest()->post(
            $uri,
            $body + ['key' => $this->apiKey]
        );

        if ($response->failed()) {
            Log::error($response->json());
            throw new \RuntimeException('Shodan API error');
        }

        return $response->json();
    }
}
