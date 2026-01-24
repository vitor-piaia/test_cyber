<?php

namespace App\Providers;

use App\Repositories\DeviceNetworkAccessRepository;
use App\Repositories\Interfaces\DeviceNetworkAccessRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class DeviceNetworkAccessRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DeviceNetworkAccessRepositoryInterface::class,
            DeviceNetworkAccessRepository::class
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
