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
    return view('welcome');
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
Route::view('/roomandtable', 'tables&rooms.index')->name('roomandtable');
Route::view('/services', 'services.index')->name('services');
