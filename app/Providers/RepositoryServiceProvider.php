<?php

namespace App\Providers;

use App\Models\FixedAssetPurchase;
use App\Repositories\Uom\UomRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Area\AreaRepository;
use App\Repositories\Item\ItemRepository;
use App\Repositories\Menu\MenuRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Staff\StaffRepository;
use App\Repositories\Entity\EntityRepository;
use App\Repositories\Account\AccountInterface;

use App\Repositories\Account\AccountRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\CashBook\CashBookInterface;
use App\Repositories\Supplier\SupplierInterface;
use App\Repositories\Uom\UomRepositoryInterface;
use App\Repositories\CashBook\CashBookRepository;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Supplier\SupplierRepository;

use App\Repositories\Transfer\TransferRepository;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Item\ItemRepositoryInterface;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\Complaint\ComplaintRepository;
use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Order\OrderRepositoryInterface;

use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;

use App\Repositories\Entity\EntityRepositoryInterface;
use App\Repositories\HeadAccount\HeadAccountInterface;

use App\Repositories\Transaction\TransactionInterface;
use App\Repositories\HeadAccount\HeadAccountRepository;

use App\Repositories\RoomSession\RoomSessionRepository;
use App\Repositories\Transaction\TransactionRepository;

use App\Repositories\Invoice\InvoiceRepositoryInterface;
use App\Repositories\Notification\NotificationInterface;

use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;

use App\Repositories\Transfer\TransferRepositoryInterface;
use App\Repositories\PurchaseOrder\PurchaseOrderRepository;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use App\Repositories\Inventory\InventoryRepositoryInterface;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\RoomSession\RoomSessionRepositoryInterface;
use App\Repositories\ItemUsageForecast\ItemUsageForecastInterface;
use App\Repositories\ItemUsageForecast\ItemUsageForecastRepository;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepository;
use App\Repositories\PurchaseOrder\PurchaseOrderRepositoryInterface;
use App\Repositories\FixedAssetPurchase\FixedAssetPurchaseRepository;
use App\Repositories\PurchaseOrderItemLeft\PurchaseOrderItemLeftInterface;
use App\Repositories\PurchaseOrderItemLeft\PurchaseOrderItemLeftRepository;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryInterface;
use App\Repositories\FixedAssetPurchase\FixedAssetPurchaseRepositoryInterface;
use App\Repositories\Pack\PackRepository;
use App\Repositories\Pack\PackRepositoryInterface;

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
        $this->app->bind(MenuRepositoryInterface::class,MenuRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class,InvoiceRepository::class);
        $this->app->bind(RoomSessionRepositoryInterface::class,RoomSessionRepository::class);
        $this->app->bind(OrderRepositoryInterface::class,OrderRepository::class);
        $this->app->bind(ItemUsageForecastInterface::class,ItemUsageForecastRepository::class);
        $this->app->bind(HeadAccountInterface::class,HeadAccountRepository::class);
        $this->app->bind(AccountInterface::class,AccountRepository::class);
        $this->app->bind(TransactionInterface::class,TransactionRepository::class);
        $this->app->bind(CashBookInterface::class,CashBookRepository::class);
        $this->app->bind(SupplierInterface::class,SupplierRepository::class);
        $this->app->bind(NotificationInterface::class,NotificationRepository::class);
        $this->app->bind(PurchaseOrderItemLeftInterface::class,PurchaseOrderItemLeftRepository::class);
        $this->app->bind(FixedAssetPurchaseRepositoryInterface::class,FixedAssetPurchaseRepository::class);
        $this->app->bind(PackRepositoryInterface::class,PackRepository::class);
    }
}
