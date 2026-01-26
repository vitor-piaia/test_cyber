<?php

namespace App\Integrations\Shodan;

use Illuminate\Support\Facades\Http;
use App\Integrations\Shodan\Interfaces\ShodanClientInterface;
use Illuminate\Support\Facades\Log;

class ShodanClient implements ShodanClientInterface
{
    protected function baseRequest()
    {
        return Http::baseUrl(config('shodan.base_url'))
            ->timeout(config('shodan.timeout'));
    }

    public function get(string $uri, array $query = []): array
    {
        $response = $this->baseRequest()
            ->get($uri, array_merge($query, [
                'key' => config('shodan.api_key'),
            ]));

        if ($response->failed()) {
            Log::error($response->json());
            throw new \RuntimeException('Shodan API error');
        }

        return $response->json();
    }

    public function post(string $uri, array $body = []): array
    {
        $response = $this->baseRequest()
            ->post($uri, $body + ['key' => config('shodan.api_key')]);

        if ($response->failed()) {
            throw new \RuntimeException('Shodan API error');
        }

        return $response->json();
    }
}
