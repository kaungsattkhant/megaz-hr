<?php

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
    return redirect()->route('staff');
});


// test

Route::view('/test', 'staff.index');

Route::view('/staff', 'staff.index')->name('staff');
Route::view('/staff/create', 'staff.create')->name('staff.crate');
Route::view('/tasks', 'tasks.index')->name('tasks');
Route::view('/departments', 'departments.index')->name('departments');
Route::view('/areas', 'areas.index')->name('areas');
Route::view('/roles', 'roles.index')->name('roles');
Route::view('/inventories', 'inventories.index')->name('inventories');
Route::view('/inventories/{inventory_id}/ledger', 'inventories.inventory_ledger')->name('inventory.ledger');
Route::view('/room_and_table', 'tables&rooms.index')->name('roomandtable');
Route::view('/services', 'services.index')->name('services');
Route::view('/menus', 'menus.index')->name('menus');
Route::view('/menus/create', 'menus.create')->name('menus.create');
Route::view('/complains', 'complains.index')->name('complains');
Route::view('/items', 'items.index')->name('items');
Route::view('/purchase_orders', 'purchase_orders.index')->name('purchase_orders');
Route::view('/purchase_orders/create', 'purchase_orders.create')->name('purchase_orders.create');

//pos
Route::view('/pos/home', 'pos.home.index')->name('pos');
Route::view('/pos/customer', 'pos.customers.index')->name('customer');
Route::view('/pos/customer/create', 'pos.customers.create')->name('customer_create');
