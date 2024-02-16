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

Route::view('/staff', 'staff.index');
Route::view('/staff/create', 'staff.create');
Route::view('/tasks', 'tasks.index');
Route::view('/departments', 'departments.index');
Route::view('/roles', 'roles.index');
Route::view('/testareas', 'areas.index');
Route::view('/inventory', 'inventories.index');