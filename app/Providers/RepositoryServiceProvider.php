<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Area\AreaRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->app->bind(AreaRepositoryInterface::class, AreaRepository::class);
    }
}
