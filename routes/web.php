<?php

use App\Http\Controllers\WEB\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    // return view('welcome');
    if (Auth::check()) {
        return redirect()->route('staff');
    } else {
        return redirect()->route('login');
    }
    // return redirect()->route('staff');
});
Route::view('/login', 'auth.login')->name('login_form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::view('/test', 'staff.index');

#change middleware
Route::middleware(['departments:role'])->group(function () {
    Route::view('/roles', 'roles.index')->name('roles');
});

Route::middleware(['departments:staff'])->group(function () {
    Route::view('/staff', 'staff.index')->name('staff');
    Route::view('/staff/create', 'staff.create')->name('staff.crate');
    Route::view('/staff/{id}/edit', 'staff.edit')->name('staff.edit');
});

Route::middleware(['departments:uom'])->group(function () {
    Route::view('/uoms', 'item_uoms.index')->name('uoms');
});

Route::middleware(['departments:item'])->group(function () {
    Route::view('/items', 'items.index')->name('items');
});

Route::middleware(['departments:task'])->group(function () {
    Route::view('/tasks', 'tasks.index')->name('tasks');
});

Route::middleware(['departments:department'])->group(function () {
    Route::view('/departments', 'departments.index')->name('departments');
});

Route::middleware(['departments:area'])->group(function () {
    Route::view('/areas', 'areas.index')->name('areas');
});

Route::middleware(['departments:inventory'])->group(function () {
    Route::view('/inventories', 'inventories.index')->name('inventories');
    Route::view('/inventories/{inventory_id}/ledger', 'inventories.inventory_ledger')->name('inventory.ledger');
});

Route::middleware(['departments:inventory-transfer-list'])->group(function () {
    Route::view('/inventory_transfers', 'transfers.index')->name('transfers.index');
    Route::view('/inventory_transfers_list', 'transfers.transfers_list')->name('transfers.transfers');
    Route::view('/inventory_receives_list', 'transfers.receives_list')->name('transfers.receives');
    Route::view('/used_defected_items', 'used_defected_items.index')->name('used_defected_items.index');
});

Route::middleware(['departments:inventory-receive-list'])->group(function () {
    // Route::view('/inventory_receives_list', 'transfers.receives_list')->name('transfers.receives');
});
Route::middleware(['departments:inventory-confirmation'])->group(function () {
});
#changed middleware

Route::middleware(['departments:complaint'])->group(function () {

    Route::view('/complaints', 'complains.index')->name('complains');

});
Route::middleware(['departments:service'])->group(function () {
    Route::view('/services', 'services.index')->name('services');
});
Route::middleware(['departments:uom-conversion'])->group(function () {
    Route::view('/uom_conversions', 'item_uoms.index')->name('uom_conversions');
});

Route::middleware(['departments:menu'])->group(function () {
    Route::view('/menus', 'menus.index')->name('menus');
    Route::view('/menus/create', 'menus.create')->name('menus.create');
    Route::view('/menus/{id}/edit', 'menus.edit')->name('menus.edit');
});
Route::middleware(['departments:table'])->group(function () {
    Route::view('/tables', 'tables&rooms.table')->name('table');
});
Route::middleware(['departments:room'])->group(function () {
    Route::view('/rooms', 'tables&rooms.index')->name('room');
});

Route::middleware(['departments:item-usage-forecast'])->group(function () {
    Route::view('/item_usage_forecasts', 'item_usage_forecastings.index')->name('item_usage_forecasts');
    Route::view('/item_usage_forecasts/{forecastId}/detail', 'item_usage_forecastings.detail')->name('item_usage_forecasts.detail');
    Route::view('/item_usage_forecasts/create', 'item_usage_forecastings.create')->name('item_usage_forecasts.create');
});
Route::middleware(['departments:purchase-order'])->group(function () {
    Route::view('/purchase_orders', 'purchase_orders.index')->name('purchase_orders');
    Route::view('/purchase_orders/create', 'purchase_orders.create')->name('purchase_orders.create');
    Route::view('/purchase_orders/{poId}/confirm', 'purchase_orders.confirm')->name('purchase_orders.confirm');
    Route::view('/purchase_orders/{poId}/buy', 'purchase_orders.buy')->name('purchase_orders.buy');
});

Route::middleware(['departments:purchase-order-confirmation'])->group(function () {
    Route::view('/confirm_purchase_order_items', 'purchase_orders.confirm_poitems')->name('purchase_orders.confirm_poitems');
});
Route::middleware(['departments:purchase-order-item-left'])->group(function () {
    Route::view('/purchase_order_left_items', 'purchase_orders.left_items_index')->name('purchase_orders.left_items_index');
});
Route::middleware(['departments:purchase-order-item-left-confirmation'])->group(function () {
    Route::view('/purchase_order_left_items/{poId}', 'purchase_orders.left_items_detail')->name('purchase_orders.left_items_detail');
});

Route::middleware(['departments:supplier'])->group(function () {
    Route::view('/suppliers', 'supplier.index')->name('suppliers.index');
    Route::view('/suppliers/create', 'supplier.create')->name('suppliers.create');
    Route::view('/suppliers/{id}/edit', 'supplier.edit')->name('suppliers.edit');
});
Route::middleware(['departments:cashbook'])->group(function () {
    Route::view('/accounting', 'accounting.index')->name('accountings');
    Route::view('/financial_transaction', 'financial_transaction.index')->name('financial_transactions');
    Route::view('/cashbook', 'cashbook.index')->name('cashbook');
    Route::view('/cashbook/office', 'cashbook.cash_office')->name('office_cash');
    Route::view('/cashbook/owner', 'cashbook.cash_owner')->name('owner_cash');
    Route::view('/cashbook/service', 'cashbook.cash_service')->name('service_cash');
    Route::view('/cashbook/advance', 'cashbook.cash_advance')->name('advance_cash');
    Route::view('/cashbook/agm', 'cashbook.cash_agm')->name('agm_cash');
    Route::view('/cashbook/gm', 'cashbook.cash_gm')->name('gm_cash');
    Route::view('/cashbook/ktv_project', 'cashbook.cash_ktv_project')->name('ktv_project_cash');

    Route::view('/bankbook/kbz_special_md_gm', 'cashbook.bank_kbz_special_md_gm')->name('kbz_special_bank');
    Route::view('/bankbook/kbz_old_gm', 'cashbook.bank_kbz_old_gm')->name('kbz_old_gm_bank');
    Route::view('/bankbook/kpay', 'cashbook.bank_kpay')->name('kpay_bank');
});
Route::middleware(['departments:fixed-asset'])->group(function () {
    Route::view('/fixed_assets', 'fixed_assets.index')->name('fixed_assets.index');
});

// Route::middleware(['departments:all_departments'])->group(function () {
//     Route::view('/inventory_transfers', 'transfers.index')->name('transfers.index');
//     Route::view('/inventory_transfers_list', 'transfers.transfers_list')->name('transfers.transfers');
//     Route::view('/inventory_receives_list', 'transfers.receives_list')->name('transfers.receives');
// });

//pos
Route::group(['prefix' => 'pos'], function () {
    Route::view('/login', 'pos.auth.index')->name('pos.login');
    Route::middleware(['departments:pos'])->group(function () {
        Route::view('/home', 'pos.home.index')->name('pos.index');
        Route::view('/customer', 'pos.customers.index')->name('pos.customers');
        Route::view('/customer/create', 'pos.customers.create')->name('pos.customers.create');
        Route::view('/ar', 'pos.AR.index')->name('pos.ar');
        Route::view('/cashbook', 'pos.cashbook.index')->name('pos.cashbooks');
        Route::view('/cashbook/detail', 'pos.cashbook.detail')->name('pos.cashbooks.detail');
        Route::view('/invoices', 'pos.invoices.index')->name('pos.invoices');
        Route::view('/invoices/detail', 'pos.invoices.detail')->name('pos.invoices.detail');
    });
});




//test
Route::view('/packages', 'packages.index');
Route::view('/packagescreate', 'packages.create');
Route::view('/menuandservicediscount', 'menu_and_service_discount.index');
Route::view('/room_discount', 'room_discount.index');