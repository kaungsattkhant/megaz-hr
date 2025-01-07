<?php

use App\Http\Controllers\WEB\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for yfgour application. These
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
    Route::view('/items/{id}/pricing_history', 'items.pricing_history')->name('items.pricing_history');
    Route::view('/items/{id}/suppliers', 'items.item_suppliers')->name('items.item_suppliers');
    Route::view('/items/{id}/suppliers/{supplierId}/brands', 'items.supplier_brands')->name('items.supplier_brands');
});

Route::middleware(['departments:task'])->group(function () {
    Route::view('/tasks', 'tasks.index')->name('tasks');
    Route::view('/tasks/reports', 'tasks.report')->name('tasks.report');
    Route::view('/custom_tasks', 'tasks.customtask')->name('custom_tasks');
    Route::view('/task_reports', 'tasks.tasks')->name('task_report');
});

Route::middleware(['departments:department'])->group(function () {
    Route::view('/departments', 'departments.index')->name('departments');
});

Route::middleware(['departments:area'])->group(function () {
    Route::view('/areas', 'areas.index')->name('areas');
});

Route::middleware(['departments:inventory'])->group(function () {
    Route::view('/inventories', 'inventories.index')->name('inventories');
    // Route::view('/inventories/{inventory_id}/ledger', 'inventories.inventory_ledger')->name('inventory.ledger');
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
Route::middleware(['departments:inventory-confirmation'])->group(function () { });
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
Route::middleware(['departments:mrp'])->group(function () {
    Route::view('/menu_categories', 'menu_categories.index')->name('menu_categories');
    Route::view('/mrp', 'MRP.index')->name('MRP');
    Route::view('/mrp/create', 'MRP.create')->name('MRP.create');
    Route::view('/mrp/{id}/edit', 'MRP.edit');
});
Route::middleware(['departments:table'])->group(function () {
    Route::view('/tables', 'tables&rooms.table')->name('table');
});
Route::middleware(['departments:room'])->group(function () {
    Route::view('/rooms', 'tables&rooms.index')->name('room');
});

Route::middleware(['departments:item-usage-forecast'])->group(function () {
    Route::view('/item_usage_forecasts', 'item_usage_forecastings.index')->name('item_usage_forecasts');
    Route::view('/item_usage_forecasts_by_month', 'item_usage_forecastings.listbyMonth')->name('item_usage_forecasts_by_month');
    Route::view('/item_usage_forecasts/{month}', 'item_usage_forecastings.listbyMonthWithDepartment')->name('item_usage_forecasts_by_month_with_department');
    Route::view('/item_usage_forecasts_with_month/{month}/department/{department}', 'item_usage_forecastings.listByMonthAndDepartment')->name('item_usage_forecasts_by_month_and_department');

    Route::view('/item_usage_forecasts/{forecastId}/detail', 'item_usage_forecastings.detail')->name('item_usage_forecasts.detail');
    Route::view('/create_item_usage_forecasts', 'item_usage_forecastings.create');
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
    Route::view('/asset_items/create', 'fixed_assets.asset_items')->name('fixed_assets.asset_items');
    Route::view('/assets/create', 'fixed_assets.assets')->name('fixed_assets.assets');
    Route::view('/asset/list', 'fixed_assets.asset_list')->name('assetList');
    Route::view('/asset_items/list', 'fixed_assets.asset_item_list')->name('assetItemList');
});
Route::middleware(['departments:account-payables'])->group(function () {
    Route::view('/account_payables', 'AP.index')->name('AP.index');
    Route::view('/account_payables/suppliers/{supplierId}/transactions', 'AP.history')->name('AP.history');
});

// Route::middleware(['departments:all_departments'])->group(function () {
//     Route::view('/inventory_transfers', 'transfers.index')->name('transfers.index');
//     Route::view('/inventory_transfers_list', 'transfers.transfers_list')->name('transfers.transfers');
//     Route::view('/inventory_receives_list', 'transfers.receives_list')->name('transfers.receives');
// });

//pos
Route::group(['prefix' => 'pos'], function () {
    Route::view('/login', 'pos.auth.index')->name('pos.login');
    // Route::middleware(['departments:pos'])->group(function () {
    Route::view('/home', 'pos.home.home')->name('pos.index');
    Route::view('/home_new', 'pos.home.home')->name('pos.home');
    Route::view('/customer', 'pos.customers.index')->name('pos.customers');
    Route::view('/customer/create', 'pos.customers.create')->name('pos.customers.create');
    Route::view('/ar', 'pos.AR.index')->name('pos.ar');
    Route::view('/cashbook', 'pos.cashbook.index')->name('pos.cashbooks');
    Route::view('/cashbook/detail', 'pos.cashbook.detail')->name('pos.cashbooks.detail');
    Route::view('/invoices', 'pos.invoices.index')->name('pos.invoices');
    Route::view('/invoices/detail', 'pos.invoices.detail')->name('pos.invoices.detail');
    // });
});


Route::middleware(['departments:inventory-stocks'])->group(function () {
    Route::view('/inventory_stocks', 'inventory_stocks.index')->name('inventory_stocks.index');
});

Route::middleware(['departments:package'])->group(function () {
    Route::view('/packages', 'packages.index')->name('packages.index');
    Route::view('/packages/create', 'packages.create')->name('packages.create');
});

Route::middleware(['departments:menu-service-discount'])->group(function () {
    Route::view('/menu_service_discounts', 'menu_and_service_discount.index')->name('menu_service_discount.index');
});
Route::middleware(['departments:room-discount'])->group(function () {
    Route::view('/room_discounts', 'room_discount.index')->name('room_discount.index');
});

//test
Route::view('/crm/customers', 'CRM.customers.index')->name('crm.customers.index');
Route::view('/crm/customers/{id}/detail', 'CRM.customers.detail')->name('crm.customers.detail');
Route::view('/crm/customers/birthdays', 'CRM.customers.birthdays')->name('crm.customers.birthdays');
Route::view('/crm/level_discounts', 'CRM.level_discounts.index')->name('crm.level_discounts.index');
Route::view('/crm/birthday_promotions', 'CRM.birthday_discounts.index')->name('crm.birthday_discounts.index');


Route::view('/booking', 'pos.booking.index');
Route::view('/booking/create', 'pos.booking.create');
Route::view('/food_orders', 'pos.menu_order.index');
Route::view('/food_orders/create', 'pos.menu_order.create');
Route::view('/delivery_charges', 'delivery_charges.index');

Route::middleware(['departments:journal'])->group(function () {
    Route::view('/journals', 'journals.index')->name('journal');
});

Route::middleware(['departments:staff-balance'])->group(function () {
    Route::view('/advanced', 'advanced.index')->name('advance');
});

Route::view('/advanced/{id}/detail', 'advanced.detail');

Route::middleware(['departments:prepaid'])->group(function () {
    Route::view('prepaid', 'prepaid.index')->name('prepaid');
});

Route::middleware(['departments:ar'])->group(function () {
    Route::view('/account_receivable', 'AR.index')->name('account_receivable');
});

Route::view('/account_receivable/{id}/detail', 'AR.detail');
// Route::middleware(['departments:financial-report'])->group(function () {
Route::view('/cash_flow_statement', 'cash_flow_statement.index');
Route::view('/indirect_cashflow_statement', 'cash_flow_statement.indirect_cashflow_statement')->name('indirect_cashflow_statement');

// });

Route::middleware(['departments:asset-depreciation-balance'])->group(function () {
    Route::view('/asset_depreciation_balance_list', 'asset_depreciation_balance.index')->name('asset_list');
});


// test
Route::middleware(['departments:skill'])->group(function () {
    Route::view('/skill', 'skill.index')->name('skill');
});

Route::middleware(['departments:cooking-place'])->group(function () {
    Route::view('/cooking_places', 'cookingPlace.index')->name('cookingPlace');
});

Route::view('/cooking_places/create', 'cookingPlace.create')->name('cookingPlaceCreate');
Route::view('/cooking_places/{id}/edit', 'cookingPlace.edit')->name('cookingPlaceCreate');

Route::middleware(['departments:duty'])->group(function () {
    Route::view('/duty', 'duty.index')->name('duty');
    Route::view('/duty/create', 'duty.create')->name('dutyCreate');
    Route::view('/duty/{id}/edit', 'duty.edit')->name('dutyEdit');
});

Route::middleware(['departments:sale-target'])->group(function () {
    Route::view('/sale_target_position', 'sale_target_position.index')->name('sale_target_position');
    Route::view('/sale_target_position/create', 'sale_target_position.create')->name('sale_target_position/create');
    Route::view('/sale_target_position/{id}/edit', 'sale_target_position.edit')->name('sale_target_position/edit');
    Route::view('/sale_target_menu', 'sale_target_menu.index')->name('sale_target_menu');
    Route::view('/sale_target_menu/create', 'sale_target_menu.create')->name('sale_target_menu/create');
    Route::view('/sale_target_menu/{id}/edit', 'sale_target_menu.edit')->name('sale_target_menu/edit');
});


Route::view('/menu_sale_report', 'menu_sale_report.index')->name('menu_sale_report.index');
Route::view('/menu_costing', 'menu_costing.index')->name('menu_costing.index');

Route::view('/pos_order_items', 'pos.orderItem.index');


Route::view('/menu_position', 'menu_position.index')->name('menu_position');
Route::view('/menu_position/create', 'menu_position.create')->name('menu_position.create');
Route::middleware(['departments:accessory'])->group(function () {
    Route::view('/accessories/create', 'accessories.create');
    Route::view('/accessories/{id}/edit', 'accessories.edit');
    Route::view('/accessories', 'accessories.index');
});




Route::middleware(['departments:objective'])->group(function () {
    Route::view('/OKR', 'OKR.index')->name('OKR');
    Route::view('/OKR/create', 'OKR.create')->name('OKR.create');
    Route::view('/OKR/{id}/edit', 'OKR.edit');
});
Route::view('/menu_forecasting', 'menu_forecastings.index')->name('menu_forecasting');
Route::view('/menu_forecasting/create', 'menu_forecastings.create')->name('menu_forecasting.create');
Route::view('/menu_forecasting/{id}/edit', 'menu_forecastings.edit')->name('menu_forecasting.edit');
Route::view('/ktv_forecasting', 'ktv_forecastings.index')->name('ktv_forecasting');
Route::view('/ktv_forecasting/create', 'ktv_forecastings.create')->name('ktv_forecasting.create');
Route::view('/ktv_forecasting/{id}/edit', 'ktv_forecastings.edit')->name('ktv_forecasting.edit');
Route::middleware(['departments:objective'])->group(function () {
    Route::view('/ktv_product_tree', 'ktv_product_tree.index')->name('ktv_product_tree');
    Route::view('/ktv_product_tree/create', 'ktv_product_tree.create')->name('ktv_product_tree.create');
    Route::view('/ktv_product_tree/{id}/edit', 'ktv_product_tree.edit');
});






//test

Route::view('/creditor', 'creditor.index')->name('creditor');
Route::view('/creditor/suppliers/{creditorId}/transactions', 'creditor.history')->name('creditor.history');

Route::view('/okr_duty', 'okr_duty.index')->name('okr_duty');
Route::view('/okr_duty/create', 'okr_duty.create')->name('okr_duty.create');
Route::view('/okr_duty/{id}/edit', 'okr_duty.edit');
Route::view('/time_shift', 'time_shift.index')->name('time_shift');
Route::view('/gps', 'GPS.index')->name('gps');
Route::view('/check_in', 'check_in.index')->name('check_in');