<?php

namespace App\Providers;

use App\Repositories\DeviceRepository;
use App\Repositories\Interfaces\DeviceRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class DeviceRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DeviceRepositoryInterface::class,
            DeviceRepository::class
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
