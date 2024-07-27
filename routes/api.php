<?php

use App\Models\Gender;
use App\Models\AreaType;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Division;
use App\Models\Township;
use App\Models\AreaCategory;
use App\Models\MenuCategory;
use App\Models\ServiceCategory;
use App\Models\UsedDefectedItem;
use App\Models\ComplaintCategory;
use App\Models\PurchaseOrderItemLeft;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\AssetItemController;
use App\Http\Controllers\API\AdsAPIController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\UomAPIController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\ItemAPIController;
use App\Http\Controllers\API\MenuAPIController;
use App\Http\Controllers\API\PackAPIController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\CashbookController;
use App\Http\Controllers\API\OrderAPIController;
use App\Http\Controllers\API\StaffAPIController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\EntityAPIController;
use App\Http\Controllers\API\BookingAPIController;
use App\Http\Controllers\API\FeatureAPIController;
use App\Http\Controllers\API\InvoiceAPIController;
use App\Http\Controllers\API\PackageAPIController;
use App\Http\Controllers\API\ProfileAPIController;
use App\Http\Controllers\API\SubAccountController;
use App\Http\Controllers\API\CustomerAPIController;
use App\Http\Controllers\API\ExcelImportController;
use App\Http\Controllers\API\HeadAccountController;
// use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\TransferAPIController;
use App\Http\Controllers\API\ComplaintAPIController;
use App\Http\Controllers\API\FoodOrderAPIController;
use App\Http\Controllers\API\InventoryAPIController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\AccountPayableController;
use App\Http\Controllers\API\MenuCategoryAPIController;
use App\Http\Controllers\API\RoomDiscountAPIController;
use App\Http\Controllers\API\UsedDefectedAPIController;
use App\Http\Controllers\API\PurchaseOrderAPIController;
use App\Http\Controllers\API\DeliveryChargeAPIController;
use App\Http\Controllers\API\ItemUsageForecastController;
use App\Http\Controllers\API\BirthDayPromotionAPIController;

use App\Http\Controllers\API\FixedAssetPurchaseAPIController;
use App\Http\Controllers\API\PurchaseOrderItemLeftController;
use App\Http\Controllers\API\MenuServiceDiscountAPIController;
use App\Http\Controllers\API\CustomerLevelDiscountAPIController;
use App\Http\Controllers\API\Customers\AuthController as CustomerAuthController;
use App\Http\Controllers\API\Customers\AdsAPIController as CustomerAdsAPIController;
use App\Http\Controllers\API\Customers\MenuAPIController as CustomersMenuAPIController;
use App\Http\Controllers\API\Customers\CustomerAPIController as UserAppCustomerAPIController;
use App\Http\Controllers\API\Customers\PackageAPIController as CustomersPackageAPIController;
use App\Http\Controllers\API\Customers\MenuCategoryAPIController as CustomerMenuCategoryAPIController;

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

Route::get('/categories', function () {
    ResponseData(Category::all());
});

Route::get('/complaint_categories', function () {
    ResponseData(ComplaintCategory::all());
});

Route::get('genders', function () {
    ResponseData(Gender::all());
});

Route::get('/service_categories', function () {
    ResponseData(ServiceCategory::all());
});

Route::get('/area_categories', function () {
    ResponseData(AreaCategory::all());
});

Route::get('/area_types', function () {
    ResponseData(AreaType::all());
});

Route::get('/menu_categories',[MenuCategoryAPIController::class,'getMenuCategories']);

Route::get('/divisions', function () {
    ResponseData(Division::with('townships')->get());
});

// Route::post('/customer_login',[CustomerAuthController::class,'customerLogin']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/areas/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);
    // Route::get('/api/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);

    Route::get('task_list', [TaskController::class, 'getTaskList']);


    Route::post('/tasks/{taskId}/update_status', [TaskController::class, 'updateTaskStatus']);
    Route::get('/profile', [ProfileAPIController::class, 'getProfile']);
    Route::post('/profile/change_password', [ProfileAPIController::class, 'updatePassword']);

    Route::get('/staff/complaints', [ComplaintAPIController::class, 'getStaffComplaints']);
    Route::post('/complaints', [ComplaintAPIController::class, 'createComplain']);
    Route::put('/complaints/{id}', [ComplaintAPIController::class, 'updateComplain']);
    Route::delete('/complaints/{id}', [ComplaintAPIController::class, 'deleteComplain']);
    Route::post('/complaints/{id}/update_status', [ComplaintAPIController::class, 'complainStatusChange']);

    Route::get('/supervisor/staff', [StaffAPIController::class, 'getStaffListBySupervisor']);
    Route::get('/supervisor/staff/{staffId}/tasks', [TaskController::class, 'getStaffTasksBySupervisor']);
    // Route::get('/task_list', [TaskController::class, 'getStaffTasksBySupervisor']);

    Route::controller(TaskController::class)->group(function()
    {
        Route::post('/tasks/{id}/double_checked','taskDoubleChecked');
    });
    Route::controller(PurchaseOrderAPIController::class)->group(function () {
        Route::get('/purchase_orders', 'getPurchaseOrder');
        Route::post('/purchase_orders', 'createPurchaseOrder');
        // below the route perform update, and kitchen data update and financial update and it depends on condition,
        Route::put('/purchase_orders/{id}', 'updatePurchaseOrder');
        Route::get('/purchase_orders/{purchase_order}', 'detail');
        Route::delete('/purchase_orders/{id}', 'deletePurchaseOrder');
        Route::get('/purchase_orders_items', 'getPurchaseOrderItem');
        Route::post('/purchase_orders_items', 'createPurchaseOrderItem');
        Route::delete('/purchase_orders_items/{id}', 'deletePurchaseOrderItem');
        Route::post('purchase_orders_bought', 'boughtPurchaseOrder');
        Route::post('updateIsCheck', 'updateIsCheck');
        Route::get('/purchase_order_item_confirmation_list', 'getPurchaseOrderItemConfirmationList');
        Route::get('/confirm_purchase_order_item', 'confirmPurchaseOrderItem');

    });
    #item usage forecast
    Route::resource('item_usage_forecasts', ItemUsageForecastController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(ItemUsageForecastController::class)->group(function () {
        Route::delete('forecast_item/{id}', 'deleteForecastItem');
    });
    Route::resource('head_accounts', HeadAccountController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('sub_accounts', SubAccountController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('accounts', AccountController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(AccountController::class)->group(function () {
        Route::get('sub_account_by_head_account/{id}', 'getSubAccountByHeadAccount');
        Route::get('get_cash_account', 'getCashAccount');
        Route::get('account_by_sub_account/{id}', 'accountBySubAccount');
        Route::post('create_second_account','createSecondAccount');
        Route::post('create_third_account','createThirdAccount');
        Route::get('get_second_account/{account_id}','getSecondAccount');
        Route::get('get_third_account/{account_id}','getThirdAccount');
    });
    Route::controller(AssetController::class)->group(function () {
        Route::post('create_asset_item','createAssetItem');
        Route::post('create_asset','createAsset');
    });
    Route::controller(AccountPayableController::class)->group(function () {
        Route::get('get_payable_account', 'getPayableAccount');
        Route::get('account_payables','index');
        Route::post('create_payable_account','createPayableAccount');
        Route::get('account_payable_transaction_list','listOfAccountPayableTransaction');
        Route::post('create_payable_transaction','createPayableTransaction');
    });

    Route::resource('transactions', TransactionController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(TransactionController::class)->group(function () {
        Route::post('transaction_confirmed', 'transactionConfirmed');
    });

    Route::controller(CashbookController::class)->group(function () {
        Route::get('cash_books', 'index');
        Route::get('close_cashbook_transaction', 'closeTransaction');
    });
    Route::controller(CommonController::class)->group(function () {
        Route::post('is_active', 'toggleIsActive');
    });

    Route::controller(FixedAssetPurchaseAPIController::class)->group(function () {
        Route::get('/fixed_asset_purchases', 'getFixedAssetPurchaseData');
        Route::post('/fixed_asset_purchases','createFixedAssetPurchaseData');
        Route::post('/fixed_asset_purchases/is_update_checked','updateIsCheck');
        Route::post('/fixed_asset_purchases/bought','boughtFixedAsset');
    });

    Route::post('/packs',[PackAPIController::class,'createPack']);
    Route::get('/packs',[PackAPIController::class,'getPacksData']);

    Route::resource('suppliers', SupplierController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('notifications',NotificationController::class)->only(['index']);
    Route::post('notifications/set_seen', [NotificationController::class, 'setSeenNotifications']);
    Route::post('notifications/{notificationId}/mark_read', [NotificationController::class, 'markReadNotification']);

    #po itemleft
    Route::resource('purchase_order_item_lefts', PurchaseOrderItemLeftController::class)->only(['index', 'show',]);

    #transfer
    Route::resource('transfers', TransferAPIController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(TransferAPIController::class)->group(function () {
        Route::get('/transfer_confirmation_list', 'transferConfirmationList');
        Route::get('/confirm_transfer_item', 'confirmTransferItem');
        Route::get('/cancel_transfer_item', 'cancelTransferItem');
    });

    Route::get('/inventories', [InventoryAPIController::class, 'getInventoryData']);
    Route::get('/get_inventory', [InventoryAPIController::class, 'getInventory']);
    Route::post('/inventories', [InventoryAPIController::class, 'createInventory']);
    Route::get('/inventories/{inventory}', [InventoryAPIController::class, 'detail']);
    Route::put('/inventories/{id}', [InventoryAPIController::class, 'updateInventory']);
    Route::delete('/inventories/{id}', [InventoryAPIController::class, 'deleteInventory']);
    Route::get('/inventories/{inventoryId}/ledgers', [InventoryAPIController::class, 'getInventoryLedgers']);
    Route::get('inventory_list',[InventoryAPIController::class, 'inventoryList']);
    Route::controller(InventoryAPIController::class)->group(function () {
        Route::get('inventory_ledger_list','getInventoryLedgerList');
    });

    Route::get('/uoms', [UomAPIController::class, 'getUomData']);
    Route::post('/uoms',[UomAPIController::class,'createUom']);
    Route::post('/uoms/{id}',[UomAPIController::class,'updateUom']);
    Route::delete('/uoms/{id}', [UomAPIController::class, 'deleteUom']);


    Route::controller(MenuServiceDiscountAPIController::class)->group(function () {
        Route::get('/menu_service_discounts', 'getMenuServiceDiscountData');
        Route::post('/menu_service_discounts', 'createMenuServiceDiscount');
        Route::post('/menu_service_discounts/{id}','editMenuServiceDiscount');
        Route::delete('/menu_service_discounts/{id}','deleteMenuServiceDiscount');
    });

    Route::controller(RoomDiscountAPIController::class)->group(function ()
    {
        Route::get('/room_discounts','getRoomDiscount');
        Route::post('/room_discounts','createRoomDiscount');
        Route::post('/room_discounts/{id}','editRoomDiscount');
        Route::delete('/room_discounts/{id}','deleteRoomDiscount');
    });

    Route::controller(MenuCategoryAPIController::class)->group(function ()
    {
        Route::get('/menu_categories','getMenuCategories');
        Route::post('/menu_categories','createMenuCategory');
        Route::post('/menu_categories/{id}','editMenuCategory');
        Route::delete('/menu_categories/{id}','deleteMenuCategory');
    });


    // uom conversion
    Route::post('/uom_conversions', [UomAPIController::class, 'createUomConversion']);
    Route::post('/uom_conversions/{id}',[UomAPIController::class,'updateUomConversion']);
    Route::get('/uom_conversions',[UomAPIController::class,'getUomConversionList']);

    Route::get('/bookings',[BookingAPIController::class,'bookingList']);
    Route::post('/bookings',[BookingAPIController::class,'createBooking']);
    Route::post('/booking_status/{id}',[BookingAPIController::class,'bookingStatusChange']);
    Route::post('/booking_active/{id}',[BookingAPIController::class,'bookingActivate']);
});

Route::controller(PackageAPIController::class)->group(function()
    {
        Route::get('/packages','getPackage');
        Route::get('/packages/{id}','detailPackage');
        Route::post('/packages','createPackage');
        Route::post('/packages/{id}','editPackage');
        Route::delete('/packages/{id}','deletePackage');
    });

Route::controller(AdsAPIController::class)->group(function()
{
    Route::get('/ads','getAds');
    Route::post('/ads','createAds');
    Route::post('/ads/{id}','editAds');
    Route::delete('/ads/{id}','deleteAds');
});


Route::controller(ExcelImportController::class)->group(function () {
    Route::post('/import_account', 'importAccount');
});

// Route::post('purchase_orders', [PurchaseOrderAPIController::class, 'createPurchaseOrder']);
Route::get('/areas', [AreaController::class, 'getAreas']);
Route::get('/area_types/{id}/areas',[AreaController::class,'getAreaByAreaType']);
Route::get('/area_categories/{id}/areas',[AreaController::class,'getAreaByAreaCategory']);
Route::post('/areas', [AreaController::class, 'createArea']);
Route::put('/areas/{id}', [AreaController::class, 'updateArea']);
Route::delete('/areas/{id}', [AreaController::class, 'deleteArea']);
Route::get('areas_by_department/{department_id}',[AreaController::class,'getAreaByDepartment']);

Route::get('/departments', [DepartmentAPIController::class, 'getDepartmentData']);
Route::post('/departments', [DepartmentAPIController::class, 'createDepartment']);
Route::post('/departments/{id}', [DepartmentAPIController::class, 'updateDepartment']);

Route::get('/roles', [RoleAPIController::class, 'getRoleData']);
Route::get('/role_by_department/{department_id}', [RoleAPIController::class, 'getRoleByDepartment']);
Route::post('/roles', [RoleAPIController::class, 'createRole']);
Route::put('/roles/{id}', [RoleAPIController::class, 'updateRole']);

Route::get('/staffs', [StaffAPIController::class, 'getStaffData']);
Route::get('/staffs/{id}',[StaffAPIController::class,'detailStaff']);
Route::post('/staffs', [StaffAPIController::class, 'createStaff']);
Route::post('/staffs/{id}', [StaffAPIController::class, 'updateStaff']);
Route::delete('/staffs/{id}', [StaffAPIController::class, 'deleteStaff']);
Route::delete('/staffs/{staff_id}/roles/{role_id}',[StaffAPIController::class,'deleteRoleStaff']);
Route::delete('/staffs/{staff_id}/inventories/{inventory_id}',[StaffAPIController::class,'deleteInventoryStaff']);
Route::delete('/staffs/{staff_id}/features/{feature_id}',[StaffAPIController::class,'deleteFeatureStaff']);


// Route::get('/tasks', [TaskController::class, 'getTaskData']);
// Route::post('/tasks', [TaskController::class, 'createTask']);
// Route::put('/tasks/{id}', [TaskController::class, 'updateTask']);
// Route::delete('/tasks/{id}', [TaskController::class, 'deleteTask']);
Route::controller(TaskController::class)->group(function () {
    Route::get('/tasks', 'getTaskData');
    Route::post('/tasks', 'createTask');
    Route::put('/tasks/{id}', 'updateTask');
    Route::delete('/tasks/{id}','deleteTask');
    Route::get('task_report','taskReport');
});
Route::get('/complaints', [ComplaintAPIController::class, 'getComplainData']);

Route::get('/entities', [EntityAPIController::class, 'getEntityData']);
Route::post('/entities', [EntityAPIController::class, 'createEntity']);
Route::put('/entities/{id}', [EntityAPIController::class, 'updateEntity']);
Route::delete('/entities/{id}', [EntityAPIController::class, 'deleteEntity']);



Route::controller(ItemAPIController::class)->group(function () {
    Route::get('item_price_list_by_item/{item_id}','getItemPriceListByItem');
});
Route::get('/items', [ItemAPIController::class, 'getItemData']);
Route::post('/items', [ItemAPIController::class, 'createItem']);
Route::put('/items/{id}', [ItemAPIController::class, 'updateItem']);
Route::delete('/items/{id}', [ItemAPIController::class, 'deleteItem']);
Route::post('/item_prices/{id}',[ItemAPIController::class,'addItemPrice']);
Route::get('/get_uom_conversion_by_uom',[UomAPIController::class,'getUomConversionByUom']);

// Route::get('/transfers', [TransferAPIController::class, 'getTransferData']);
// Route::post('/transfers', [TransferAPIController::class, 'createTransfer']);
// Route::put('/transfers/{id}', [TransferAPIController::class, 'updateTransfer']);
// Route::delete('/transfers/{id}', [TransferAPIController::class, 'deleteTransfer']);
// Route::post('/transfers/{id}/confirms', [TransferAPIController::class, 'confirmTransfer']);

Route::get('/customers', [CustomerAPIController::class, 'getCustomerData']);
Route::post('/customers', [CustomerAPIController::class, 'createCustomer']);
Route::put('/customers/{id}', [CustomerAPIController::class, 'updateCustomer']);
Route::delete('/customers/{id}', [CustomerAPIController::class, 'deleteCustomer']);
Route::controller(CustomerAPIController::class)->group(function () {
    Route::get('customer_list','listOfCustomer');
    Route::get('/customer_list/{id}','detailCustomer');
});

Route::get('/menus', [MenuAPIController::class, 'getMenus']);
Route::post('/menus', [MenuAPIController::class, 'createMenu']);
Route::post('/menus/{id}/add_price', [MenuAPIController::class, 'addPriceToMenu']);
Route::post('/menus/{id}/is_active',[MenuAPIController::class,'menuOnOff']);
Route::get('/menus/{id}',[MenuAPIController::class,'detailMenu']);
Route::post('/menus/{id}',[MenuAPIController::class,'menuEdit']);
Route::post('/menu/{id}/is_feature',[MenuAPIController::class,'featureToggleMenu']);


Route::get('/areas/{id}/entities', [EntityAPIController::class, 'getEntityWithInvoice']);
Route::get('/entities/{id}', [EntityAPIController::class, 'getEntityDetail']);
Route::get('/areas/{id}/inactive_entities', [EntityAPIController::class, 'getOnlyInactiveEntities']);

Route::post('/entities/start', [InvoiceAPIController::class, 'startEntity']);
Route::post('/entities/orders', [OrderAPIController::class, 'addOrder']);
Route::post('/entities/add_more_sessions', [InvoiceAPIController::class, 'addMoreSessions']);
Route::post('/entities/change', [InvoiceAPIController::class, 'changeRoom']);
Route::post('/entities/done', [InvoiceAPIController::class, 'endRoom']);
Route::post('/room_done',[InvoiceAPIController::class,'doneRoom']);
Route::post('/entities/confirm',[InvoiceAPIController::class,'roomConfirm']);

Route::get('/order_items',[OrderAPIController::class,'getOrderItemList']);

Route::get('/invoices', [InvoiceAPIController::class, 'getInvoiceData']);

// Route::group(['prefix' => 'management'], function () {});
Route::get("/test", [TestController::class, "index"]);

Route::get('/menu_categories/{id}/menus',[MenuAPIController::class,'menuByMenuCategory']);
Route::get('/menu_categories_bookings/{id}/menus',[MenuAPIController::class,'menuByMenuCategoryBooking']);

Route::post("/order_status_change",[OrderAPIController::class,'orderItemChangeStatus']);

Route::get('/used_defected_items',[UsedDefectedAPIController::class,'lisltUsedDefectedItem']);
Route::post('/used_defected_items',[UsedDefectedAPIController::class,'createUsedDefected']);
Route::post('/used_defected_items/{id}/confirm',[UsedDefectedAPIController::class,'usedDefectConfirm']);


Route::get('get_inventory',[InventoryAPIController::class, 'getInventory']);
// feature
Route::get('/features',[FeatureAPIController::class,'getFeatureData']);

// customer upcoming birthday list
Route::get('/crm/upcoming_birthdays',[CustomerAPIController::class,'upComingBdList']);
Route::controller(BirthDayPromotionAPIController::class)->group(function ()
{
    Route::get('/birthday_promotions','getBirthdayPromotions');
    Route::post('/birthday_promotions','createBDPromotion');
    Route::post('/birthday_promotions/{id}','updateBDPromotion');
    Route::delete('/birthday_promotions/{id}','deleteBDPromotion');
});

Route::controller(CustomerLevelDiscountAPIController::class)->group(function ()
{
    Route::get('/customer_level_discounts','getCustomerLevelDiscountData');
    Route::post('/customer_level_discounts','createCustomerLevelDiscount');
    Route::post('/customer_level_discounts/{id}','updateCustomerLevelDiscount');
    Route::delete('/customer_level_discounts/{id}','deleteCustomerLevelDiscount');
});

Route::controller(FoodOrderAPIController::class)->group(function()
{
    Route::get('/food_orders','listAllFoodOrder');
    Route::post('/food_orders','createFoodOrder');
    Route::post('/food_order_items/{id}','confirmFoodOrderItem');
    Route::post('/food_orders/{id}','confirmFoodOrder');
});

Route::controller(DeliveryChargeAPIController::class)->group(function()
{
    Route::get('/delivery_charges','getDeliveryChargeData');
    Route::post('/delivery_charges','createDeliveryCharge');
});

Route::post('send_notification', [NotificationController::class, 'sendNotification']);


