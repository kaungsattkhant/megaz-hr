<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\StaffAPIController;
use App\Http\Controllers\API\TaskController;

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

    Route::get('/areas', [AreaController::class, 'getAreas']);
    Route::get('/areas/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);
});

Route::get('/departments',[DepartmentAPIController::class,'getDepartmentData']);
Route::post('/departments',[DepartmentAPIController::class,'createDepartment']);
Route::put('/departments/{id}',[DepartmentAPIController::class,'updateDepartment']);


Route::get('/roles',[RoleAPIController::class,'getRoleData']);
Route::post('/roles',[RoleAPIController::class,'createRole']);
Route::put('/roles/{id}',[RoleAPIController::class,'updateRole']);

Route::get('/staff',[StaffAPIController::class,'getStaffData']);
Route::post('/staff',[StaffAPIController::class,'createStaff']);
Route::put('/staff/{id}',[StaffAPIController::class,'updateStaff']);
Route::delete('/staff/{id}',[StaffAPIController::class,'deleteStaff']);

// Route::group(['prefix' => 'management'], function () {});
