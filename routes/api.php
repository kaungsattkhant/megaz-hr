<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\ComplaintAPIController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\StaffAPIController;
use App\Http\Controllers\API\TaskController;
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

    Route::get('/areas', [AreaController::class, 'getAreas']);
    Route::get('/areas/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);
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
Route::delete('/staffs/{id}',[StaffAPIController::class,'deleteStaff']);

Route::get('genders', function(){
    ResponseData(Gender::all());
});

Route::get('/tasks',[TaskController::class,'getTaskData']);
Route::post('/tasks',[TaskController::class,'createTask']);
Route::put('/tasks/{id}',[TaskController::class,'updateTask']);
Route::delete('/tasks/{id}',[TaskController::class,'deleteTask']);

Route::get('/complains',[ComplaintAPIController::class,'getComplainData']);
Route::post('/complains',[ComplaintAPIController::class,'createComplain']);
Route::put('/complains/{id}',[ComplaintAPIController::class,'updateComplain']);
Route::delete('/complains/{id}',[ComplaintAPIController::class,'deleteComplain']);

// Route::group(['prefix' => 'management'], function () {});
