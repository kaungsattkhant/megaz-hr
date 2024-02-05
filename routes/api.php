<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\StaffAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class,'login']);
Route::middleware('auth:api')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/departments',[DepartmentAPIController::class,'getDepartmentData']);
Route::post('/departments',[DepartmentAPIController::class,'createDepartment']);
Route::put('/departments/{id}',[DepartmentAPIController::class,'updateDepartment']);


Route::get('/roles',[RoleAPIController::class,'getRoleData']);
Route::post('/roles',[RoleAPIController::class,'createRole']);
Route::put('/roles/{id}',[RoleAPIController::class,'updateRole']);

Route::get('/staffs',[StaffAPIController::class,'getStaffData']);
Route::post('/staffs',[StaffAPIController::class,'createStaff']);
Route::put('/staffs/{id}',[StaffAPIController::class,'updateStaff']);
Route::delete('/staffs/{staff}',[StaffAPIController::class,'deleteStaff']);

// Route::group(['prefix' => 'management'], function () {});
