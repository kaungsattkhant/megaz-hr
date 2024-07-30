<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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
        Relation::enforceMorphMap([
            'department' => 'App\Models\Department',
            'area' => 'App\Models\Area',
            'purchase_order' => 'App\Models\PurchaseOrder',
            'staff' => 'App\Models\Staff',
            'purchase_order_item'=>'App\Models\PurchaseOrderItem',
            'account'=>'App\Models\Account',
            'category'=>'App\Models\Category',
            'customer'=>'App\Models\Customer',
            'entity'=>'App\Models\Entity',
            'inventory'=>'App\Models\Inventory',
            'item'=>'App\Models\Item',
            'menu_category'=>'App\Models\MenuCategory',
            'menu'=>'App\Models\Menu',
            'sub_account'=>'App\Models\SubAccount',
            'task'=>'App\Models\Task',
            'uom'=>'App\Models\UOM',
            'invoice'=>'App\Models\Invoice',
            'po_grn'=>'App\Models\PoGrn',
            'asset'=>'App\Models\Asset',
        ]);
    }
}
