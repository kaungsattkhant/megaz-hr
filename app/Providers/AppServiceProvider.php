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
        ]);
    }
}
