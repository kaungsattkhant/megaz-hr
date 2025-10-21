<?php

use App\Models\Gender;
use App\Models\AreaType;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Division;
use App\Models\Township;
use App\Models\Complaint;
use App\Models\AreaCategory;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\UsedDefectedItem;
use App\Models\ComplaintCategory;
use App\Models\PurchaseOrderItemLeft;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BankController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\AssetItemController;
use App\Http\Controllers\API\AdsAPIController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\UomAPIController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AccruedController;
use App\Http\Controllers\API\CanteenController;
use App\Http\Controllers\API\CreditorControler;
use App\Http\Controllers\API\DutyAPIController;
use App\Http\Controllers\API\ItemAPIController;
use App\Http\Controllers\API\MenuAPIController;
use App\Http\Controllers\API\PackAPIController;
use App\Http\Controllers\API\PoOrderController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\CashbookController;
use App\Http\Controllers\API\CreditorController;
// use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\API\OrderAPIController;
use App\Http\Controllers\API\SkillAPIController;
use App\Http\Controllers\API\StaffAPIController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\AccessoryController;
use App\Http\Controllers\API\EntityAPIController;
use App\Http\Controllers\API\ObjectiveController;
use App\Http\Controllers\API\BookingAPIController;
use App\Http\Controllers\API\FeatureAPIController;
use App\Http\Controllers\API\InvoiceAPIController;
use App\Http\Controllers\API\JournalAPIController;
use App\Http\Controllers\API\PackageAPIController;
use App\Http\Controllers\API\PrepaidAPIController;
use App\Http\Controllers\API\ProfileAPIController;
use App\Http\Controllers\API\SubAccountController;
use App\Http\Controllers\API\CustomerAPIController;
use App\Http\Controllers\API\ExcelImportController;
use App\Http\Controllers\API\HeadAccountController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\TransferAPIController;
use App\Http\Controllers\API\ComplaintAPIController;
use App\Http\Controllers\API\FoodOrderAPIController;
use App\Http\Controllers\API\InventoryAPIController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\DepartmentAPIController;
use App\Http\Controllers\API\AccountPayableController;
use App\Http\Controllers\API\CookingPlaceAPIController;
use App\Http\Controllers\API\MenuCategoryAPIController;
use App\Http\Controllers\API\RoomDiscountAPIController;
use App\Http\Controllers\API\SellingExtraAPIController;
use App\Http\Controllers\API\StaffAdvanceAPIController;
use App\Http\Controllers\API\UsedDefectedAPIController;
use App\Http\Controllers\API\PurchaseOrderAPIController;
use App\Http\Controllers\API\DeliveryChargeAPIController;
use App\Http\Controllers\API\ItemUsageForecastController;
use App\Http\Controllers\API\SaleTargetMenuAPIController;
use App\Http\Controllers\API\SaleTargetResultAPIController;
use App\Http\Controllers\API\AccountReceivableAPIController;
use App\Http\Controllers\API\AssetInventoryLedgerController;
use App\Http\Controllers\API\BirthDayPromotionAPIController;
use App\Http\Controllers\API\FixedAssetPurchaseAPIController;
use App\Http\Controllers\API\PurchaseOrderItemLeftController;
use App\Http\Controllers\API\SaleTargetPositionAPIController;
use App\Http\Controllers\API\MenuServiceDiscountAPIController;
use App\Http\Controllers\API\CustomerLevelDiscountAPIController;
use App\Http\Controllers\API\MaterialRequirementsPlanningAPIController;
use App\Http\Controllers\API\Customers\AuthController as CustomerAuthController;
use App\Http\Controllers\API\Customers\AdsAPIController as CustomerAdsAPIController;
use App\Http\Controllers\API\Customers\MenuAPIController as CustomersMenuAPIController;
use App\Http\Controllers\API\Customers\CustomerAPIController as UserAppCustomerAPIController;
use App\Http\Controllers\API\Customers\PackageAPIController as CustomersPackageAPIController;
use App\Http\Controllers\API\Customers\MenuCategoryAPIController as CustomerMenuCategoryAPIController;
use App\Http\Controllers\API\ReportController;

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

Route::get('/area_categories', function (Request $request) {
    ResponseData(
        AreaCategory::when($request->area_type, function ($query, $areaType) {
            // $query->where('name', $areaType);
        })->get()
    );
});

Route::get('/area_types', function () {
    ResponseData(AreaType::all());
});

Route::get('/menu_categories', [MenuCategoryAPIController::class, 'getMenuCategories']);

Route::get('/divisions', function () {
    ResponseData(Division::with('townships')->get());
});

// Route::post('/customer_login',[CustomerAuthController::class,'customerLogin']);

Route::post('/login', [AuthController::class, 'login']);
Route::controller(FeatureAPIController::class)->group(function () {
    Route::post('/feature_import', 'featureImport');
});
Route::middleware('auth:api')->group(function () {
    Route::controller(AccruedController::class)->group(function () {
        Route::get('get_expense_accounts', 'getExpenseAccount');
        Route::post('accruals', 'createAccrued');
        Route::get('accruals', 'getAccrued');
        Route::get('accruals/{accountId}', 'detailAccrued');
        Route::get('other-payable-accounts', 'getOtherPayable');
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/areas/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);
    // Route::get('/api/{areaId}/tasks', [TaskController::class, 'getTasksOfRolesFromArea']);

    Route::get('task_list', [TaskController::class, 'getTaskList']);


    Route::post('/tasks/{taskId}/update_status', [TaskController::class, 'updateTaskStatus']);
    Route::get('/profile', [ProfileAPIController::class, 'getProfile']);
    Route::post('/profile/change_password', [ProfileAPIController::class, 'updatePassword']);

    Route::get('/staff/complaints', [ComplaintAPIController::class, 'getStaffComplaints']);
    Route::post('/complaints', [ComplaintAPIController::class, 'createComplain']);
    Route::get('/complaints/{id}', [ComplaintAPIController::class, 'complaintDetail']);
    Route::post('/complaints/{id}', [ComplaintAPIController::class, 'updateComplain']);
    Route::delete('/complaints/{id}', [ComplaintAPIController::class, 'deleteComplain']);
    Route::post('/complaints/{id}/update_status', [ComplaintAPIController::class, 'complainStatusChange']);
    Route::get('/complaints_responsibles', [ComplaintAPIController::class, 'complaintResponsiblesByStaff']);
    Route::get('/complaints_carbon_copies', [ComplaintAPIController::class, 'complaintCarbonCopiesByStaff']);

    // deleted related complaint
    Route::delete('/complaints_images/{id}', [ComplaintAPIController::class, 'deleteComplaintImage']);
    Route::delete('/complaints_responsibles/{id}', [ComplaintAPIController::class, 'deleteComplaintResponsible']);
    Route::delete('/complaints_carbon_copies/{id}', [ComplaintAPIController::class, 'deleteComplaintCarbonCopy']);


    Route::get('/staffs_by_role', [StaffAPIController::class, 'getStaffListBySupervisor']);
    Route::get('/supervisor/staff/{staffId}/tasks', action: [TaskController::class, 'getStaffTasksBySupervisor']);
    // Route::get('/task_list', [TaskController::class, 'getStaffTasksBySupervisor']);

    Route::controller(TaskController::class)->group(function () {
        Route::post('/tasks/{id}/double_checked', 'taskDoubleChecked');
        Route::post('/custom_tasks', 'createCustomTask');
        Route::get('/custom_tasks', 'getCustomTasks');
        Route::get('/custom_tasks/{id}', 'customTaskDetail');
        Route::post('/custom_tasks/{id}', 'updateCustomTask');

        // add image
        Route::post('/tasks/{id}/add_images', 'addTaskImage');
        Route::get('/tasks/{id}/images', 'getTaskImages');
        Route::delete('/tasks_images/{id}', 'deleteTaskImage');
    });
    Route::controller(PurchaseOrderAPIController::class)->group(function () {
        Route::post('/purchase_orders_items/check_limitation', 'checkLimitation');
        Route::get('/purchase_orders', 'getPurchaseOrder');
        Route::post('/purchase_orders', 'createPurchaseOrder');
        // below the route perform update, and kitchen data update and financial update and it depends on condition,
        Route::put('/purchase_orders/{id}', 'updatePurchaseOrder');
        Route::get('/purchase_orders/{purchase_order}', 'detail');
        Route::delete('/purchase_orders/{id}', 'deletePurchaseOrder');
        Route::get('/purchase_orders_items', 'getPurchaseOrderItem');
        Route::post('/purchase_orders_items', 'createPurchaseOrderItem');
        Route::delete('/purchase_orders_items/{id}', 'deletePurchaseOrderItem');
        // Route::post('purchase_orders_bought', 'boughtPurchaseOrder');
        Route::post('updateIsCheck', 'updateIsCheck');
        Route::get('/purchase_order_item_confirmation_list', 'getPurchaseOrderItemConfirmationList');
        Route::get('/confirm_purchase_order_item', 'confirmPurchaseOrderItem');
        Route::get('/items/{itemId}/brands/{brandId}', 'getAvgPriceByBrand');
    });
    Route::controller(EventController::class)->group(function () {
        Route::get('/events', 'eventLists');
        Route::post('/events', 'createOrUpdateEvent');
    });
    #item usage forecast
    Route::resource('item_usage_forecasts', ItemUsageForecastController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(ItemUsageForecastController::class)->group(function () {
        Route::delete('forecast_item/{id}', 'deleteForecastItem');
        Route::get('/item_usage_forecast_items_by_month', 'itemUsageForecastListByMonth');
        Route::get('/item_usage_forecast_items_with_month/{month}', 'itemUsageForecastListByMonthwithDepartment');
        Route::get('/item_usage_forecast_items_with_month/{month}/department/{department_id}', 'iufWithMonthAndDepartment');
    });
    Route::resource('head_accounts', HeadAccountController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('sub_accounts', SubAccountController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('accounts', AccountController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(AccountController::class)->group(function () {
        Route::get('sub_account_by_head_account/{id}', 'getSubAccountByHeadAccount');
        Route::get('get_cash_account', 'getCashAccount');
        Route::get('account_by_sub_account/{id}', 'accountBySubAccount');
        Route::post('create_second_account', 'createSecondAccount');
        Route::post('create_third_account', 'createThirdAccount');
        Route::get('get_second_account', 'getSecondAccount');
        Route::get('get_third_account', 'getThirdAccount');
        Route::get('get_depreciation_account_list', 'getDepreciationAccountList');
        Route::post('create_prepaid_account', 'createPrePaidAccount');
        Route::get('/prepaid_account_list', 'prepaidAccountList');
        Route::get('/ar_sub_accounts', 'getSubAccountForAr');
    });
    Route::controller(AssetController::class)->group(function () {
        Route::post('create_asset_item', 'createAssetItem');
        Route::post('create_asset', 'createAsset');
        Route::get('get_asset_item_by_account', 'getAssetItemByAccount');
        Route::get('/assets', 'getAsset');
        Route::get('/asset_items', 'getAssetItem');
        Route::post('/add_depreciation', 'addDepreciation');
        Route::get('/get_depreciation_balance', 'getDepreciationBalance');
        Route::get('/add_depreciation_balance', 'addDepreciationBalance');
    });
    Route::controller(AssetInventoryLedgerController::class)->group(function () {
        Route::get('asset_inventory_ledger_list', 'index');
    });
    Route::controller(AccountPayableController::class)->group(function () {
        Route::get('get_payable_account', 'getPayableAccount');
        Route::post('create_payable_account', 'createPayableAccount');
        Route::get('account_payables', 'index');
        Route::get('account_payable_transaction_list', 'listOfAccountPayableTransaction');
        Route::post('create_payable_transaction', 'createPayableTransaction');
    });

    Route::resource('transactions', TransactionController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(TransactionController::class)->group(function () {
        Route::post('transaction_confirmed', 'transactionConfirmed');
    });

    Route::controller(CashbookController::class)->group(function () {
        Route::get('cash_books', 'index');
        Route::post('close_cashbook_transaction', 'closeTransaction');
    });
    Route::controller(CommonController::class)->group(function () {
        Route::post('is_active', 'toggleIsActive');
    });


    Route::controller(FixedAssetPurchaseAPIController::class)->group(function () {
        Route::get('/fixed_asset_purchases', 'getFixedAssetPurchaseData');
        Route::post('/fixed_asset_purchases', 'createFixedAssetPurchaseData');
        Route::post('/fixed_asset_purchases/is_update_checked', 'updateIsCheck');
        Route::post('/fixed_asset_purchases/bought', 'boughtFixedAsset');
    });


    Route::resource('suppliers', SupplierController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::controller(SupplierController::class)->group(function () {
        Route::post('/create_supplier_account', 'createSupplierAccount');
        Route::post('/toggle_brand_item', 'toggleBrandItem');
        Route::post('/suppliers/toggle_phones', 'toggleSupplierPhone');
        Route::post('/suppliers/toggle_bank_accounts', 'toggleSupplierBankAccount');
        Route::post('/suppliers/import', 'supplierImport');
        Route::post('/brands/import', 'brandImport');
    });
    Route::resource('notifications', NotificationController::class)->only(['index']);
    Route::get('notification_by_user', [NotificationController::class, 'notificationUsersData']);

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
    Route::get('/inventory/all', [InventoryAPIController::class, 'getallInventories']);
    Route::get('/inventories', [InventoryAPIController::class, 'getInventoryData']);
    Route::get('/get_inventory', [InventoryAPIController::class, 'getInventory']);
    Route::post('/inventories', [InventoryAPIController::class, 'createInventory']);
    Route::get('/inventories/{inventory}', [InventoryAPIController::class, 'detail']);
    Route::put('/inventories/{id}', [InventoryAPIController::class, 'updateInventory']);
    Route::delete('/inventories/{id}', [InventoryAPIController::class, 'deleteInventory']);
    Route::get('/inventories/{inventoryId}/ledgers', [InventoryAPIController::class, 'getInventoryLedgers']);
    Route::get('inventory_list', [InventoryAPIController::class, 'inventoryList']);
    Route::get('area-equipments/{areaId}', [InventoryAPIController::class, 'getInventoryItemsByStaff']);
    Route::controller(InventoryAPIController::class)->group(function () {
        Route::get('inventory_ledger_list', 'getInventoryLedgerList');
        Route::post('inventory_item', 'createInventoryItem');
        Route::get('inventory-closing-items', 'getInventoryClosingItems');
    });

    Route::get('/uoms', [UomAPIController::class, 'getUomData']);
    Route::post('/uoms', [UomAPIController::class, 'createUom']);
    Route::post('/uoms/{id}', [UomAPIController::class, 'updateUom']);
    Route::delete('/uoms/{id}', [UomAPIController::class, 'deleteUom']);


    Route::controller(MenuServiceDiscountAPIController::class)->group(function () {
        Route::get('/menu_service_discounts', 'getMenuServiceDiscountData');
        Route::post('/menu_service_discounts', 'createMenuServiceDiscount');
        Route::post('/menu_service_discounts/{id}', 'editMenuServiceDiscount');
        Route::delete('/menu_service_discounts/{id}', 'deleteMenuServiceDiscount');
    });

    Route::controller(RoomDiscountAPIController::class)->group(function () {
        Route::get('/room_discounts', 'getRoomDiscount');
        Route::post('/room_discounts', 'createRoomDiscount');
        Route::post('/room_discounts/{id}', 'editRoomDiscount');
        Route::delete('/room_discounts/{id}', 'deleteRoomDiscount');
    });

    Route::controller(MenuCategoryAPIController::class)->group(function () {
        Route::get('/menu_categories', 'getMenuCategories');
        Route::post('/menu_categories', 'createMenuCategory');
        Route::post('/menu_categories/{id}', 'editMenuCategory');
        Route::delete('/menu_categories/{id}', 'deleteMenuCategory');
    });


    // uom conversion
    Route::post('/uom_conversions', [UomAPIController::class, 'createUomConversion']);
    Route::post('/uom_conversions/{id}', [UomAPIController::class, 'updateUomConversion']);
    Route::get('/uom_conversions', [UomAPIController::class, 'getUomConversionList']);

    Route::get('/bookings', [BookingAPIController::class, 'bookingList']);
    Route::post('/bookings', [BookingAPIController::class, 'createBooking']);
    Route::post('/booking_status/{id}', [BookingAPIController::class, 'bookingStatusChange']);
    Route::post('/booking_active/{id}', [BookingAPIController::class, 'bookingActivate']);
    Route::controller(JournalAPIController::class)->group(function () {
        Route::get('/journals', 'listAllJournals');
        Route::post('/journals', 'createJournal');
    });

    Route::controller(StaffAdvanceAPIController::class)->group(function () {
        Route::post('/staff_advances', 'createStaffAdvance');
    });

    Route::controller(PrepaidAPIController::class)->group(function () {
        Route::get('/prepaid_lists', 'prepaidList');
        Route::post('/prepaids', 'createPrepaid');
        Route::post('/prepaid_payments', 'createPrepaidPayment');
    });

    Route::controller(AccountReceivableAPIController::class)->group(function () {
        Route::post("/account_receivables", 'createAccountReceivable');
        Route::post('/paid_account_receivables', 'paidAccountReceivable');
        Route::get('/account_receivable_lists', 'accountReceivableList');
        Route::get('/account/{id}/account_receivable_lists', 'accountReceivableDetail');
    });

    Route::controller(SkillAPIController::class)->group(function () {
        Route::get('/skills', 'listAllSkills');
        Route::post('/skills', 'createSkill');
        Route::get('/skills/{id}', 'skillDetail');
        Route::post('/skills/{id}', 'updateSkill');
        Route::delete('/skills/{id}', 'deleteSkill');
        Route::get('/roles/{role_id}/skills', 'skillByRole');
    });

    Route::controller(CookingPlaceAPIController::class)->group(function () {
        Route::get('/cooking_places', 'listAllCookingPlaces');
        Route::get('/cooking_places/{id}', 'detailCookingPlace');
        Route::post('/cooking_places', 'createCookingPlace');
        Route::post('/cooking_places/{id}', 'updateCookingPlace');
        Route::delete('/cooking_places/{id}', 'deleteCookingPlace');
    });

    Route::controller(DutyAPIController::class)->group(function () {
        Route::post('/duties', 'createDuty');
        Route::get('/duties', 'listDuties');
        Route::post('/duties/{id}', 'updateDuty');
        Route::delete('/duties/{id}', 'deleteDuty');
        Route::get('/duties/{id}', 'dutyDetail');
    });
    Route::resource('canteens', CanteenController::class)->only(['index', 'store', 'show']);
    Route::controller(CanteenController::class)->group(function () { });
    //service
    Route::resource('services', ServiceController::class)->only(['index', 'store', 'show']);

    //pos service api
    Route::controller(ServiceController::class)->group(function () {
        Route::get('/get_service', 'getService');
        Route::get('/service', 'getService');
    });
    Route::resource('accessories', AccessoryController::class)->only(['index', 'store', 'show']);
    Route::controller(AccessoryController::class)->group(function () {
        Route::get('/get_accessory_category', 'getAccessoryCategory');
        Route::get('/get_accessory_by_category/{accessory_category}', 'getAccessoryByCategory');
        Route::delete('/accessory_item/{id}', 'deleteAccessoryItem');
    });


    // Route::post('send_notification', [NotificationController::class, 'sendNotification']);

    Route::controller(MaterialRequirementsPlanningAPIController::class)->group(function () {
        Route::apiResource('/mrp', MaterialRequirementsPlanningAPIController::class);
        Route::post('/menu/{id}/toggle', 'menuToggle');
        Route::get('/menu_step/{id}', 'getMenuStepList');
        Route::delete('/menu_step_items/{id}', 'menuStepItemsDelete');
        Route::get('/cooking_place', 'getCookingPlace');
        Route::post('/mrp/{id}', 'updateMrpList');
        Route::get('/roles', 'getRoles');
        Route::get('/menu_category_cooking_areas', 'getMenuCategoryCookingAreas');
        Route::post('/menu_area/{menuAreaId}', 'updateMenuCategoryCookingAreas');
        Route::get('/menu_selling_areas', 'getSellingAreas');
        Route::get('/menus/{id}/prices', 'getMenuPrices');
        Route::post('/menus/{id}/prices', 'updateMenuPrices');
        Route::get('/remarks', 'getRemarks');
        Route::post('/remarks', 'createRemark');
        Route::get('/sale-reports', 'saleReport');
    });

    Route::controller(CreditorController::class)->group(function () {
        Route::get('creditors', 'index');
        Route::get('creditor_transaction_list', 'listOfAccountPayableTransaction');
        Route::post('create_creditor_transaction', 'createCreditorTransaction');

        Route::get('/get_creditor_account_list', 'getCreditorAccountList');
        Route::post('/create_creditor_account', 'createCreditorAccount');
        Route::get('/get_creditor_transaction_by_supplier', 'getCreditorTransactionBySupplier');
    });
    Route::controller(BrandController::class)->group(function () {
        Route::get('brands', 'index');
        Route::post('brands', 'createBrand');
        Route::get('/get_brand_by_item', [BrandController::class, 'getBrandByItem']);
    });
    // Route::controller(ObjectiveController::class)->group(function () {
    //     Route::get('/objectives', 'getObjectives');
    //     Route::get('/roles_department/{id}', 'getRolesByDepartmentId');
    //     Route::post('/objectives', 'store');
    //     Route::post('/objectives/{id}', 'update');
    //     Route::get('/objectives/{id}', 'getObjectiveById');
    //     Route::get('/objectives/{id}', 'getObjectiveById');
    //     Route::delete('/objectives/{id}', 'deleteObjective');

    //     //mobile-api
    //     Route::get('/daily/objectives', 'objectiveLists');
    //     Route::get('/daily/objectives_key', 'getdailyObjectives');
    //     Route::post('/daily/objective_key_staff/{id}', 'updateDailyObjective');
    //     Route::post('/objectives/images', 'storeImages');
    //     Route::post('/objectives/images/{objKeyImgId}', 'updateImages');
    // });
    Route::controller(ItemAPIController::class)->group(function () {
        Route::get('item_price_list_by_item/{item_id}', 'getItemPriceListByItem');
        Route::get('brand_by_supplier', 'brandBySupplier');
        Route::get('supplier_by_item/{item_id}', 'supplierByItem');
        Route::get('brand_list_of_supplier_by_item/{item_id}', 'brandlistOfSupplierByItem');
        Route::get('brand_list_of_by_item/{item_id}', 'brandlistOfSupplierByItem');
        Route::get('equipment-items', 'equipmentItem');
    });
    //invoice transaction
    Route::post('/invoice_transaction', [PoOrderController::class, 'processInvoiceTransaction']);
    Route::get('/sale_target_results', [SaleTargetResultAPIController::class, 'getSaleTargetResult']);

    Route::prefix('report')->controller(ReportController::class)->group(function () {

        Route::get('get_bar_for_sky', 'getBarForSky');
        Route::get('get-total-ktv-customers', 'getTotalKTVCustomers');
        Route::get('get-total-ktv-sessions', 'getTotalKTVSessions');
        Route::get('get-total-ktv-room-charges', 'getTotalKTVRoomCharges');
        Route::get('get-total-ktv-sales', 'getTotalKTVSales');
        Route::get('get_waiter_sale', 'getWaiterSale');

        // Route::get('ktv/trainings', 'getKTVTraining');
        Route::get('bar/total-expense', 'getBarTotalExpense');
    });

});

Route::controller(FeatureAPIController::class)->group(function () {
    Route::get('/features', 'getFeatureData');
    Route::post('/feature_import', 'featureImport');
    Route::get('feature_by_department/{department_id}', 'getFeatureByDepartment');
    Route::get('feature_by_module', 'getFeatureByModule');

});
Route::controller(AdsAPIController::class)->group(function () {
    Route::get('/ads', 'getAds');
    Route::post('/ads', 'createAds');
    Route::post('/ads/{id}', 'editAds');
    Route::get('/ads/{id}', 'adsDetail');
    Route::delete('/ads/{id}', 'deleteAds');
    Route::get('/latest_ads', 'latestAds');
});

Route::controller(ExcelImportController::class)->group(function () {
    Route::post('/import_account', 'importAccount');
});

Route::controller(SaleTargetPositionAPIController::class)->group(function () {
    Route::get('/sale_target_positions', 'listAllSalteTargetPosition');
    Route::get('/sale_target_positions/{id}', 'saleTargetPositionDetail');
    Route::post('/sale_target_positions', 'createSaleTargetPosition');
    Route::post('/sale_target_positions/{id}', 'updateSaleTargetPosition');
    Route::delete('/sale_target_positions/{id}', 'deleteSaleTargetPosition');
});

Route::controller(SaleTargetMenuAPIController::class)->group(function () {
    Route::get('/sale_target_menus', 'listSaleTargetMenu');
    Route::get('/sale_target_menus/{id}', 'getSaleTargetMenu');
    Route::post('/sale_target_menus', 'createSaleTargetMenu');
    Route::post('/sale_target_menus/{id}', 'updateSaleTargetMenu');
    Route::delete('/sale_target_menus/{id}', 'deleteSaleTargetMenu');
});


Route::controller(PackageAPIController::class)->group(function () {
    Route::get('/packages', 'getPackage');
    Route::get('/packages/{id}', 'detailPackage');
    Route::post('/packages', 'createPackage');
    Route::post('/packages/{id}', 'editPackage');
    Route::delete('/packages/{id}', 'deletePackage');
});

Route::get('/areas', [AreaController::class, 'getAreas']);
Route::get('/area_types/{id}/areas', [AreaController::class, 'getAreaByAreaType']);
Route::get('/area_categories/{id}/areas', [AreaController::class, 'getAreaByAreaCategory']);
Route::post('/areas', [AreaController::class, 'createArea']);
Route::put('/areas/{id}', [AreaController::class, 'updateArea']);
Route::delete('/areas/{id}', [AreaController::class, 'deleteArea']);
Route::get('areas_by_department/{department_id}', [AreaController::class, 'getAreaByDepartment']);
Route::get('/sellings_areas', [AreaController::class, 'getSellingAreas']);
Route::get('/cooking_areas', [AreaController::class, 'getCookingAreas']);


Route::get('/departments', [DepartmentAPIController::class, 'getDepartmentData']);
Route::post('/departments', [DepartmentAPIController::class, 'createDepartment']);
Route::post('/departments/{id}', [DepartmentAPIController::class, 'updateDepartment']);
Route::get('/deps', [DepartmentAPIController::class, 'getDepartments']);

Route::get('/roles', [RoleAPIController::class, 'getRoleData']);
Route::get('/role_by_department/{department_id}', [RoleAPIController::class, 'getRoleByDepartment']);
Route::post('/roles', [RoleAPIController::class, 'createRole']);
Route::post('/roles/{id}', [RoleAPIController::class, 'updateRole']);
Route::post('/roles/{roleId}/available_toggle', [RoleAPIController::class, 'roleAvailableToggle']);

Route::get('/staff_reports', [StaffAPIController::class, 'staffReport']);
Route::get('/staffs', [StaffAPIController::class, 'getStaffData']);
Route::get('/staffs/{id}', [StaffAPIController::class, 'detailStaff']);
Route::post('/staffs', [StaffAPIController::class, 'createStaff']);
Route::post('/staffs/{id}', [StaffAPIController::class, 'updateStaff']);
Route::delete('/staffs/{id}', [StaffAPIController::class, 'deleteStaff']);
Route::delete('/staffs/{staff_id}/roles/{role_id}', [StaffAPIController::class, 'deleteRoleStaff']);
Route::delete('/staffs/{staff_id}/inventories/{inventory_id}', [StaffAPIController::class, 'deleteInventoryStaff']);
Route::delete('/staffs/{staff_id}/features/{feature_id}', [StaffAPIController::class, 'deleteFeatureStaff']);
Route::get('/departments/{department_id}/staffs', [StaffAPIController::class, 'getStaffByDepartment']);
Route::get('/staff_by_department_slug/{slug}', [StaffAPIController::class, 'getStaffByDepartmentSlug']);
Route::get('/staff_balances', [StaffAPIController::class, 'staffBalanceList']);
Route::get('/staff_balances/{id}', [StaffAPIController::class, 'detailStaffBalance']);
Route::get('/staff/{id}/duties', [StaffAPIController::class, 'getStaffWithDuties']);
Route::post('/staffs/{staffId}/change_password', [StaffAPIController::class, 'changePassword']);
Route::get('/nrcs', [StaffAPIController::class, 'nrcLists']);
Route::controller(BankController::class)->group(function () {
    Route::get('/banks', 'getAllBanks');
    Route::post('/banks', 'createBank');
});

Route::get('/staff_lists', [StaffAPIController::class, 'staffList']);


// Route::get('/tasks', [TaskController::class, 'getTaskData']);
// Route::post('/tasks', [TaskController::class, 'createTask']);
// Route::put('/tasks/{id}', [TaskController::class, 'updateTask']);
// Route::delete('/tasks/{id}', [TaskController::class, 'deleteTask']);
Route::controller(TaskController::class)->group(function () {
    Route::get('/tasks', 'getTaskData');
    Route::post('/tasks', 'createTask');
    Route::put('/tasks/{id}', 'updateTask');
    Route::delete('/tasks/{id}', 'deleteTask');
    Route::get('task_report', 'taskReport');
    Route::get('/task_by_role/{role_id}', 'getTaskByRole');
});
Route::get('/complaints', [ComplaintAPIController::class, 'getComplainData']);

Route::get('/entities', [EntityAPIController::class, 'getEntityData']);
Route::post('/entities', [EntityAPIController::class, 'createEntity']);
Route::put('/entities/{id}', [EntityAPIController::class, 'updateEntity']);
Route::delete('/entities/{id}', [EntityAPIController::class, 'deleteEntity']);


Route::get('/items', [ItemAPIController::class, 'getItemData']);
Route::post('/items', [ItemAPIController::class, 'createItem']);
Route::get('/items/{id}', [ItemAPIController::class, 'detail']);
Route::put('/items/{id}', [ItemAPIController::class, 'updateItem']);
Route::delete('/items/{id}', [ItemAPIController::class, 'deleteItem']);

Route::controller(SellingExtraAPIController::class)->group(function () {
    Route::get('/selling_extra_categories', 'getSellingExtraCategories');
    Route::post('/selling_extra_categories', 'createSellingExtraCategory');
    Route::get('/selling_extras', 'getSellingExtras');
    Route::post('/selling_extras', 'createSellingExtras');
    Route::post('/selling_extras/{id}', 'updateSellingExtras');
});

Route::post('/add_item_price_by_supplier_item', [ItemAPIController::class, 'addItemPrice']);
Route::get('/get_uom_conversion_by_uom', [UomAPIController::class, 'getUomConversionByUom']);
Route::get('/get_item_type', [ItemAPIController::class, 'getItemType']);
Route::post('/categories', [ItemAPIController::class, 'createCategory']);
Route::post('/item_types', [ItemAPIController::class, 'createItemType']);
Route::post('/import/item_types', [ItemAPIController::class, 'importItemType']);
Route::post('/import/categories', [ItemAPIController::class, 'importCategory']);
Route::post('/import/uoms', [ItemAPIController::class, 'importUom']);
Route::post('/import/items', [ItemAPIController::class, 'itemImport']);
Route::post('/import/item_prices', [ItemAPIController::class, 'itemPriceImport']);

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
    Route::get('customer_list', 'listOfCustomer');
    Route::get('/customer_list/{id}', 'detailCustomer');
});

Route::get('/menus', [MenuAPIController::class, 'getMenus']);
Route::post('/menus', [MenuAPIController::class, 'createMenu']);
Route::post('/menus/{id}/add_price', [MenuAPIController::class, 'addPriceToMenu']);
Route::post('/menus/{id}/is_active', [MenuAPIController::class, 'menuOnOff']);
Route::get('/menus/{id}', [MenuAPIController::class, 'detailMenu']);
Route::post('/menus/{id}', [MenuAPIController::class, 'menuEdit']);
Route::post('/menu/{id}/is_feature', [MenuAPIController::class, 'featureToggleMenu']);
Route::get('/menu_report', [MenuAPIController::class, 'menuReport']);
Route::get('/menu_costing', [MenuAPIController::class, 'costingMenu']);


// Route::group(['prefix' => 'management'], function () {});
Route::get("/test", [TestController::class, "index"]);

Route::get('/menu_categories/{id}/menus', [MenuAPIController::class, 'menuByMenuCategory']);
Route::get('/menu_categories_bookings/{id}/menus', [MenuAPIController::class, 'menuByMenuCategoryBooking']);
Route::get('/menus/{menu_id}/areas', [MenuAPIController::class, 'areaByMenu']);

//moved to pos.php
// Route::post("/order_status_change", [OrderAPIController::class, 'orderItemChangeStatus']);
//end moved

Route::get('/used_defected_items', [UsedDefectedAPIController::class, 'lisltUsedDefectedItem']);
Route::post('/used_defected_items', [UsedDefectedAPIController::class, 'createUsedDefected']);
Route::post('/used_defected_items/{id}/confirm', [UsedDefectedAPIController::class, 'usedDefectConfirm']);


Route::get('get_inventory', [InventoryAPIController::class, 'getInventory']);
// feature

// customer upcoming birthday list
Route::get('/crm/upcoming_birthdays', [CustomerAPIController::class, 'upComingBdList']);
Route::controller(BirthDayPromotionAPIController::class)->group(function () {
    Route::get('/birthday_promotions', 'getBirthdayPromotions');
    Route::post('/birthday_promotions', 'createBDPromotion');
    Route::post('/birthday_promotions/{id}', 'updateBDPromotion');
    Route::delete('/birthday_promotions/{id}', 'deleteBDPromotion');
});

Route::controller(CustomerLevelDiscountAPIController::class)->group(function () {
    Route::get('/customer_level_discounts', 'getCustomerLevelDiscountData');
    Route::post('/customer_level_discounts', 'createCustomerLevelDiscount');
    Route::post('/customer_level_discounts/{id}', 'updateCustomerLevelDiscount');
    Route::delete('/customer_level_discounts/{id}', 'deleteCustomerLevelDiscount');
});

Route::controller(FoodOrderAPIController::class)->group(function () {
    Route::get('/food_orders', 'listAllFoodOrder');
    Route::post('/food_order_items/{id}', 'confirmFoodOrderItem');
    Route::post('/food_orders/{id}', 'confirmFoodOrder');
    Route::post('/create_food_orders', 'createConfirmFoodOrder');
    Route::post('/food_order_status/{id}', 'updateFoodTimeAndStatus');
    Route::get('/food_order_lists', 'foodOrderListForKitchen');
});

Route::controller(DeliveryChargeAPIController::class)->group(function () {
    Route::get('/delivery_charges', 'getDeliveryChargeData');
    Route::post('/delivery_charges', 'createDeliveryCharge');
});

Route::controller(TestController::class)->group(function () {
    Route::get('/get_holidays', 'getHolidays');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/tags', 'getTags');
    Route::post('/tags', 'createTag');
});

Route::get('push_data_to_inventory', [InventoryAPIController::class, 'pushDataInventory']);

