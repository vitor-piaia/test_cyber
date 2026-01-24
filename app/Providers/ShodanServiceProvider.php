<?php

namespace App\Providers;

use App\Integrations\Shodan\Interfaces\ShodanClientInterface;
use App\Integrations\Shodan\ShodanClient;
use Illuminate\Support\ServiceProvider;

class ShodanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ShodanClientInterface::class,
            ShodanClient::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
