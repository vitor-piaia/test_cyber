<?php

namespace App\Providers;

use App\Repositories\Interfaces\NetworkRepositoryInterface;
use App\Repositories\NetworkRepository;
use Illuminate\Support\ServiceProvider;

class NetworkRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            NetworkRepositoryInterface::class,
            NetworkRepository::class
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
