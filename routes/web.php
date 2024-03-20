<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WEB\AuthController;

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
    if(Auth::check()){
        return redirect()->route('staff');
    }
    else{
        return redirect()->route('login');
    }
    // return redirect()->route('staff');
});
Route::view('/login', 'auth.login')->name('login_form');
Route::post('/login',[AuthController::class,'login'])->name('login');

// test

Route::view('/test', 'staff.index');

Route::view('/roles', 'roles.index')->name('roles');
Route::view('/staff', 'staff.index')->name('staff');
Route::view('/staff/create', 'staff.create')->name('staff.crate');

Route::view('/tasks', 'tasks.index')->name('tasks');
Route::view('/complains', 'complains.index')->name('complains');

Route::view('/departments', 'departments.index')->name('departments');
Route::view('/areas', 'areas.index')->name('areas');

Route::view('/inventories', 'inventories.index')->name('inventories');
Route::view('/inventories/{inventory_id}/ledger', 'inventories.inventory_ledger')->name('inventory.ledger');

Route::view('/room_and_table', 'tables&rooms.index')->name('roomandtable');
Route::view('/services', 'services.index')->name('services');

Route::view('/items', 'items.index')->name('items');
Route::view('/menus', 'menus.index')->name('menus');
Route::view('/menus/create', 'menus.create')->name('menus.create');

Route::view('/purchase_orders', 'purchase_orders.index')->name('purchase_orders');
Route::view('/purchase_orders/create', 'purchase_orders.create')->name('purchase_orders.create');
Route::view('/purchase_orders/{poId}/confirm', 'purchase_orders.confirm')->name('purchase_orders.confirm');

Route::view('/item_usage_forecasts', 'item_usage_forecastings.index')->name('item_usage_forecasts');
Route::view('/item_usage_forecasts/{forecastId}/detail', 'item_usage_forecastings.detail')->name('item_usage_forecasts.detail');
Route::view('/item_usage_forecasts/create', 'item_usage_forecastings.create')->name('item_usage_forecasts.create');

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

//pos
Route::view('/pos/home', 'pos.home.index')->name('pos');
Route::view('/pos/customer', 'pos.customers.index')->name('customer');
Route::view('/pos/customer/create', 'pos.customers.create')->name('customer_create');
Route::view('/pos/ar', 'pos.AR.index')->name('pos_ar');
Route::view('/pos/cashbook', 'pos.cashbook.index')->name('cashbook');
Route::view('/pos/cashbook/detail', 'pos.cashbook.detail')->name('cashbook_detail');
Route::view('/pos/invoices', 'pos.invoices.index')->name('invoices');
Route::view('/pos/invoices/detail', 'pos.invoices.detail')->name('invoices_detail');

