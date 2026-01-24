<?php

namespace App\Providers;

use App\Repositories\DeviceNetworkMetadataRepository;
use App\Repositories\Interfaces\DeviceNetworkMetadataRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class DeviceNetworkMetadataRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DeviceNetworkMetadataRepositoryInterface::class,
            DeviceNetworkMetadataRepository::class
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
