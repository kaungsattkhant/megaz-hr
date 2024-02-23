<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Area\AreaRepository;

use App\Repositories\Complaint\ComplaintRepository;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;

use App\Repositories\Entity\EntityRepository;
use App\Repositories\Entity\EntityRepositoryInterface;

use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Inventory\InventoryRepositoryInterface;

use App\Repositories\Item\ItemRepository;
use App\Repositories\Item\ItemRepositoryInterface;

use App\Repositories\PurchaseOrder\PurchaseOrderRepository;
use App\Repositories\PurchaseOrder\PurchaseOrderRepositoryInterface;

use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepository;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryInterface;

use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;

use App\Repositories\Staff\StaffRepository;
use App\Repositories\Staff\StaffRepositoryInterface;

use App\Repositories\Task\TaskRepository;
use App\Repositories\Task\TaskRepositoryInterface;

use App\Repositories\Uom\UomRepository;
use App\Repositories\Uom\UomRepositoryInterface;

use App\Repositories\Transfer\TransferRepository;
use App\Repositories\Transfer\TransferRepositoryInterface;

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
        $this->app->bind(TaskRepositoryInterface::class,TaskRepository::class);
        $this->app->bind(ComplaintRepositoryInterface::class,ComplaintRepository::class);
        $this->app->bind(EntityRepositoryInterface::class,EntityRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class,InventoryRepository::class);
        $this->app->bind(UomRepositoryInterface::class,UomRepository::class);
        $this->app->bind(ItemRepositoryInterface::class,ItemRepository::class);
        $this->app->bind(PurchaseOrderRepositoryInterface::class,PurchaseOrderRepository::class);
        $this->app->bind(PurchaseOrderItemRepositoryInterface::class,PurchaseOrderItemRepository::class);
        $this->app->bind(TransferRepositoryInterface::class,TransferRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class,CustomerRepository::class);
    }
}
