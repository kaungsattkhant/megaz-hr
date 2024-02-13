<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\ComplaintAPIController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\EntityAPIController;
use App\Http\Controllers\API\InventoryAPIController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\ServiceAPIController;
use App\Http\Controllers\API\StaffAPIController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\ProfileAPIController;
use App\Models\Gender;

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


    Route::get('/areas/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);
    Route::post('/tasks/{taskId}/update_status', [TaskController::class, 'updateTaskStatus']);
    Route::get('/profile', [ProfileAPIController::class, 'getProfile']);
});

Route::get('/areas', [AreaController::class, 'getAreas']);

Route::get('/departments',[DepartmentAPIController::class,'getDepartmentData']);
Route::post('/departments',[DepartmentAPIController::class,'createDepartment']);
Route::put('/departments/{id}',[DepartmentAPIController::class,'updateDepartment']);


Route::get('/roles',[RoleAPIController::class,'getRoleData']);
Route::post('/roles',[RoleAPIController::class,'createRole']);
Route::put('/roles/{id}',[RoleAPIController::class,'updateRole']);

Route::get('/staffs',[StaffAPIController::class,'getStaffData']);
Route::post('/staffs',[StaffAPIController::class,'createStaff']);
Route::put('/staffs/{id}',[StaffAPIController::class,'updateStaff']);
Route::delete('/staffs/{id}',[StaffAPIController::class,'deleteStaff']);

Route::get('genders', function(){
    ResponseData(Gender::all());
});

Route::get('/tasks',[TaskController::class,'getTaskData']);
Route::post('/tasks',[TaskController::class,'createTask']);
Route::put('/tasks/{id}',[TaskController::class,'updateTask']);
Route::delete('/tasks/{id}',[TaskController::class,'deleteTask']);

Route::get('/complaints',[ComplaintAPIController::class,'getComplainData']);
Route::post('/complaints',[ComplaintAPIController::class,'createComplain']);
Route::put('/complaints/{id}',[ComplaintAPIController::class,'updateComplain']);
Route::delete('/complaints/{id}',[ComplaintAPIController::class,'deleteComplain']);
Route::post('/complaints/{id}/update_status',[ComplaintAPIController::class,'complainStatusChange']);

Route::get('/entities',[EntityAPIController::class,'getEntityData']);
Route::post('/entities',[EntityAPIController::class,'createEntity']);
Route::put('/entities/{id}',[EntityAPIController::class,'updateEntity']);
Route::delete('/entities/{id}',[EntityAPIController::class,'deleteEntity']);

Route::get('/inventories',[InventoryAPIController::class,'getInventoryData']);
Route::post('/inventories',[InventoryAPIController::class,'createInventory']);
Route::put('/inventories/{id}',[InventoryAPIController::class,'updateInventory']);
Route::delete('/inventories/{id}',[InventoryAPIController::class,'deleteInventory']);
// Route::group(['prefix' => 'management'], function () {});


