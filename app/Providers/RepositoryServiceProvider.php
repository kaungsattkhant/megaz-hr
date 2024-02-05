<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\AreaRepositoryInterface;
use App\Repositories\AreaRepository;

use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;

use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;

use App\Repositories\Staff\StaffRepository;
use App\Repositories\Staff\StaffRepositoryInterface;

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
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(RoleRepositoryInterface::class,RoleRepository::class);
        $this->app->bind(StaffRepositoryInterface::class,StaffRepository::class);
    }
}
