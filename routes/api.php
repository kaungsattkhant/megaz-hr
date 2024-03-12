<?php

use App\Models\Gender;
use App\Models\AreaType;

use App\Models\Category;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\ServiceCategory;
use App\Models\ComplaintCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\UomAPIController;
use App\Http\Controllers\API\ItemAPIController;
use App\Http\Controllers\API\MenuAPIController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\OrderAPIController;
use App\Http\Controllers\API\StaffAPIController;
use App\Http\Controllers\API\EntityAPIController;
use App\Http\Controllers\API\InvoiceAPIController;
use App\Http\Controllers\API\ProfileAPIController;
use App\Http\Controllers\API\ServiceAPIController;
use App\Http\Controllers\API\SubAccountController;
use App\Http\Controllers\API\CustomerAPIController;
use App\Http\Controllers\API\ExcelImportController;
use App\Http\Controllers\API\HeadAccountController;
use App\Http\Controllers\API\TransferAPIController;
use App\Http\Controllers\API\ComplaintAPIController;
use App\Http\Controllers\API\InventoryAPIController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\PurchaseOrderAPIController;
use App\Http\Controllers\API\ItemUsageForecastController;
use App\Http\Controllers\API\PurchaseOrderItemAPIController;

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

Route::get('/menu_categories', function(){
    ResponseData(MenuCategory::where('is_active',1)->get());
});

Route::post('/login', [AuthController::class,'login']);
Route::middleware('auth:api')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/areas/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);
    Route::post('/tasks/{taskId}/update_status', [TaskController::class, 'updateTaskStatus']);
    Route::get('/profile', [ProfileAPIController::class, 'getProfile']);
    Route::post('/profile/change_password', [ProfileAPIController::class, 'updatePassword']);

    Route::get('/staff/complaints',[ComplaintAPIController::class,'getStaffComplaints']);
    Route::post('/complaints',[ComplaintAPIController::class,'createComplain']);
    Route::put('/complaints/{id}',[ComplaintAPIController::class,'updateComplain']);
    Route::delete('/complaints/{id}',[ComplaintAPIController::class,'deleteComplain']);
    Route::post('/complaints/{id}/update_status',[ComplaintAPIController::class,'complainStatusChange']);

    Route::get('/supervisor/staff',[StaffAPIController::class,'getStaffListBySupervisor']);
    Route::get('/supervisor/staff/{staffId}/tasks',[TaskController::class,'getStaffTasksBySupervisor']);
    Route::controller(PurchaseOrderAPIController::class)->group(function(){

        Route::get('/purchase_orders','getPurchaseOrder');
        Route::post('/purchase_orders','createPurchaseOrder');
        // below the route perform update, and kitchen data update and financial update and it depends on condition,
        Route::put('/purchase_orders/{id}','updatePurchaseOrder');
        Route::get('/purchase_orders/{purchase_order}','detail');
        Route::delete('/purchase_orders/{id}','deletePurchaseOrder');

        Route::get('/purchase_orders_items','getPurchaseOrderItem');
        Route::post('/purchase_orders_items','createPurchaseOrderItem');

        Route::delete('/purchase_orders_items/{id}','deletePurchaseOrderItem');
        Route::post('updateIsCheck','updateIsCheck');
    });
    #item usage forecast
    Route::resource('item_usage_forecasts', ItemUsageForecastController::class)->only(['index','store','show','destroy']);
    Route::controller(ItemUsageForecastController::class)->group(function(){
        Route::delete('forecast_item/{id}','deleteForecastItem');
    });
});

Route::resource('head_accounts', HeadAccountController::class)->only(['index','store','show','destroy']);
Route::resource('sub_accounts', SubAccountController::class)->only(['index','store','show','destroy']);

Route::controller(ExcelImportController::class)->group(function(){
    Route::post('/import_account','importAccount');
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

Route::get('/complaints',[ComplaintAPIController::class,'getComplainData']);

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





Route::get('/transfers',[TransferAPIController::class,'getTransferData']);
Route::post('/transfers',[TransferAPIController::class,'createTransfer']);
Route::put('/transfers/{id}',[TransferAPIController::class,'updateTransfer']);
Route::delete('/transfers/{id}',[TransferAPIController::class,'deleteTransfer']);
Route::post('/transfers/{id}/confirms',[TransferAPIController::class,'confirmTransfer']);

Route::get('/customers',[CustomerAPIController::class,'getCustomerData']);
Route::post('/customers',[CustomerAPIController::class,'createCustomer']);
Route::put('/customers/{id}',[CustomerAPIController::class,'updateCustomer']);
Route::delete('/customers/{id}',[CustomerAPIController::class,'deleteCustomer']);

Route::get('/menus', [MenuAPIController::class, 'getMenus']);
Route::post('/menus', [MenuAPIController::class, 'createMenu']);
Route::post('/menus/{id}/add_price', [MenuAPIController::class, 'addPriceToMenu']);

Route::get('/rooms',[EntityAPIController::class,'getRoomsWithInvoice']);
Route::get('/rooms/{id}',[EntityAPIController::class,'getRoomDetail']);
Route::get('/rooms/lists/inactive',[EntityAPIController::class,'getOnlyInactiveRooms']);

Route::post('/rooms/start',[InvoiceAPIController::class,'startRoomSession']);
Route::post('/rooms/orders',[OrderAPIController::class,'addOrder']);
Route::post('/rooms/add_more_sessions',[InvoiceAPIController::class,'addMoreSessions']);
Route::post('/rooms/change_rooms',[InvoiceAPIController::class,'changeRoom']);
Route::post('/rooms/done',[InvoiceAPIController::class,'endRoom']);

Route::get('/tables',[EntityAPIController::class,'getTablesWithInvoice']);
// Route::group(['prefix' => 'management'], function () {});
Route::get("/test", [TestController::class, "index"]);


