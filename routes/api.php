<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\ComplaintAPIController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\EntityAPIController;
use App\Http\Controllers\API\InventoryAPIController;
use App\Http\Controllers\API\ItemAPIController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\ServiceAPIController;
use App\Http\Controllers\API\StaffAPIController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\ProfileAPIController;
use App\Http\Controllers\API\PurchaseOrderAPIController;
use App\Http\Controllers\API\PurchaseOrderItemAPIController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\TransferAPIController;
use App\Http\Controllers\API\UomAPIController;

use App\Models\ComplaintCategory;
use App\Models\AreaType;
use App\Models\Category;
use App\Models\Gender;
use App\Models\ServiceCategory;

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

Route::get('/categories',function(){
    ResponseData(Category::all());
});

Route::get('/complaint_categories',function(){
    ResponseData(ComplaintCategory::all());
});

Route::get('genders', function(){
    ResponseData(Gender::all());
});

Route::get('/service_categories',function(){
    ResponseData(ServiceCategory::all());
});

Route::get('/area_types', function(){
    ResponseData(AreaType::all());
});


Route::post('/login', [AuthController::class,'login']);
Route::middleware('auth:api')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/areas/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);
    Route::post('/tasks/{taskId}/update_status', [TaskController::class, 'updateTaskStatus']);
    Route::get('/profile', [ProfileAPIController::class, 'getProfile']);
    Route::post('/profile/change_password', [ProfileAPIController::class, 'updatePassword']);

    Route::get('/complaints',[ComplaintAPIController::class,'getComplainData']);
    Route::post('/complaints',[ComplaintAPIController::class,'createComplain']);
    Route::put('/complaints/{id}',[ComplaintAPIController::class,'updateComplain']);
    Route::delete('/complaints/{id}',[ComplaintAPIController::class,'deleteComplain']);
    Route::post('/complaints/{id}/update_status',[ComplaintAPIController::class,'complainStatusChange']);
});

Route::get('/areas', [AreaController::class, 'getAreas']);
Route::post('/areas', [AreaController::class, 'createArea']);
Route::put('/areas/{id}', [AreaController::class, 'updateArea']);
Route::delete('/areas/{id}', [AreaController::class, 'deleteArea']);

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

Route::get('/tasks',[TaskController::class,'getTaskData']);
Route::post('/tasks',[TaskController::class,'createTask']);
Route::put('/tasks/{id}',[TaskController::class,'updateTask']);
Route::delete('/tasks/{id}',[TaskController::class,'deleteTask']);

Route::get('/entities',[EntityAPIController::class,'getEntityData']);
Route::post('/entities',[EntityAPIController::class,'createEntity']);
Route::put('/entities/{id}',[EntityAPIController::class,'updateEntity']);
Route::delete('/entities/{id}',[EntityAPIController::class,'deleteEntity']);

Route::get('/inventories',[InventoryAPIController::class,'getInventoryData']);
Route::post('/inventories',[InventoryAPIController::class,'createInventory']);
Route::put('/inventories/{id}',[InventoryAPIController::class,'updateInventory']);
Route::delete('/inventories/{id}',[InventoryAPIController::class,'deleteInventory']);
Route::get('/inventories/{inventoryId}/ledgers',[InventoryAPIController::class,'getInventoryLedgers']);

Route::get('/uoms',[UomAPIController::class,'getUomData']);
Route::post('/uoms',[UomAPIController::class,'createUom']);
Route::put('/uoms/{id}',[UomAPIController::class,'updateUom']);
Route::delete('/uoms/{id}',[UomAPIController::class,'deleteUom']);

Route::get('/items',[ItemAPIController::class,'getItemData']);
Route::post('/items',[ItemAPIController::class,'createItem']);
Route::put('/items/{id}',[ItemAPIController::class,'updateItem']);
Route::delete('/items/{id}',[ItemAPIController::class,'deleteItem']);

Route::get('/purchase_orders',[PurchaseOrderAPIController::class,'getPurchaseOrder']);
Route::post('/purchase_orders',[PurchaseOrderAPIController::class,'createPurchaseOrder']);
// below the route perform update, and kitchen data update and financial update and it depends on condition,
Route::put('/purchase_orders/{id}',[PurchaseOrderAPIController::class,'updatePurchaseOrder']);
Route::delete('/purchase_orders/{id}',[PurchaseOrderAPIController::class,'deletePurchaseOrder']);

Route::get('/purchase_orders_items',[PurchaseOrderItemAPIController::class,'getPurchaseOrderItem']);
Route::post('/purchase_orders_items',[PurchaseOrderItemAPIController::class,'createPurchaseOrderItem']);
Route::put('/purchase_orders_items/{id}',[PurchaseOrderItemAPIController::class,'updatePurchaseOrderItem']);
Route::delete('/purchase_orders_items/{id}',[PurchaseOrderItemAPIController::class,'deletePurchaseOrderItem']);

Route::get('/transfers',[TransferAPIController::class,'getTransferData']);
Route::post('/transfers',[TransferAPIController::class,'createTransfer']);
Route::put('/transfers/{id}',[TransferAPIController::class,'updateTransfer']);
Route::delete('/transfers/{id}',[TransferAPIController::class,'deleteTransfer']);
Route::post('/transfers/{id}/confirms',[TransferAPIController::class,'confirmTransfer']);

// Route::group(['prefix' => 'management'], function () {});
Route::get("/test", [TestController::class, "index"]);


