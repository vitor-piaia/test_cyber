<?php

namespace App\Providers;

use App\Integrations\Shodan\ShodanClient;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;

class ShodanClientProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ShodanClient::class, function ($app) {
            return new ShodanClient(
                http: $app->make(Factory::class),
                baseUrl: config('shodan.base_url'),
                apiKey: config('shodan.api_key'),
                timeout: config('shodan.timeout', 10),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
