<?php

namespace App\Providers;

use App\Models\InvoiceService;
use Illuminate\Support\ServiceProvider;
use App\Observers\InvoiceServiceObserver;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        InvoiceService::observe(InvoiceServiceObserver::class);
        Relation::enforceMorphMap([
            'department' => 'App\Models\Department',
            'area' => 'App\Models\Area',
            'purchase_order' => 'App\Models\PurchaseOrder',
            'staff' => 'App\Models\Staff',
            'purchase_order_item' => 'App\Models\PurchaseOrderItem',
            'account' => 'App\Models\Account',
            'category' => 'App\Models\Category',
            'customer' => 'App\Models\Customer',
            'entity' => 'App\Models\Entity',
            'inventory' => 'App\Models\Inventory',
            'item' => 'App\Models\Item',
            'menu_category' => 'App\Models\MenuCategory',
            'menu' => 'App\Models\Menu',
            'sub_account' => 'App\Models\SubAccount',
            'task' => 'App\Models\Task',
            'uom' => 'App\Models\UOM',
            'invoice' => 'App\Models\Invoice',
            'po_grn' => 'App\Models\PoGrn',
            'asset' => 'App\Models\Asset',
            'skill' => 'App\Models\Skill',
            'complaint' => 'App\Models\Complaint',
            'canteen' => 'App\Models\Canteen',
            'canteen_item' => 'App\Models\CanteenItem',
            'prepaid' => 'App\Models\Prepaid',
            'account_receivable' => 'App\Models\AccountReceivable',
            'po_order' => 'App\Models\PoOrder',
            'arrival_item' => 'App\Models\ArrivalItem',
            'po_invoice' => 'App\Models\PoInvoice',
            'meeting' => 'App\Models\Meeting',
            'training' => 'App\Models\Training',
            'orgNew' => 'App\Models\OrgNew',
            'warning' => 'App\Models\Warning',
            'customer_deposit' => 'App\Models\CustomerDeposit',
            'role' => 'App\Models\Role',
            'selling_extra' => 'App\Models\SellingExtra',
            'staff_advance' => 'App\Models\StaffAdvance',
            'cashbook_transfer' => 'App\Models\CashbookTransfer',
            'staff_timeshift' => 'App\Models\StaffTimeshift',
            'staff_equipment' => 'App\Models\StaffEquipment',
            'staff_equipment_assign' => 'App\Models\StaffEquipmentAssign',
            'accrued' => 'App\Models\Accrued',
            'leave' => 'App\Models\Leave',
            'staff_equipment_handover' => 'App\Models\StaffEquipmentHandover',
            'staff_equipment_handover_item' => 'App\Models\StaffEquipmentHandoverItem',
            'lost_item' => 'App\Models\LostItem',
        ]);
    }
}
