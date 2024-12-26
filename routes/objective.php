<?php

use App\Http\Controllers\API\MRPForecastController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ObjectiveController;

Route::middleware('auth:api')->group(function () {
  Route::controller(ObjectiveController::class)->group(function () {
    //admin 
    Route::get('/objectives', 'getObjectives');
    Route::get('/roles_department/{id}', 'getRolesByDepartmentId');
    Route::post('/objectives', 'store');
    Route::post('/objectives/{id}', 'update');
    Route::get('/objectives/{id}', 'getObjectiveById');
    Route::get('/objectives/{id}', 'getObjectiveById');
    Route::delete('/objectives/{id}', 'deleteObjective');

    //mobile-api
    Route::get('/daily/objectives', 'objectiveLists');
    Route::get('/daily/objectives_key/{objId}', 'getdailyObjectives');
    // Route::get('/daily/objectives/{objId}', 'getdailyObjectivesById');
    Route::post('/daily/objectives_key_staff/{id}', 'updateDailyObjective');
    Route::post('/objectives/key_staff/{id}/images', 'storeImages');
    Route::post('/objectives/key_staff/{objKeyStaffId}/images/update', 'updateImages');
    Route::get('/objectives/key_staff/{objKeystaffId}/images', 'getObjKeyStaffImage');
    Route::delete('/objectives/key_staff/images/{id}', 'deleteObjKeystaffImage');

    //ktv-objective-tree
    Route::get('/ktv/entity_room', 'getKtvRoom');
    Route::get('/ktv/objectives/{departmentId}', 'getKtvObjective');
    Route::get('/ktv/objective_trees', 'getKtvObjectiveTree');
    Route::post('/ktv/objective_trees', 'storeKtvObjectiveTree');
    Route::get('/ktv/objective_trees/{id}', 'getKtvObjTreeById');
    Route::post('/ktv/objective_trees/{id}', 'updateKtvObjTree');
  });


  Route::controller(MRPForecastController::class)->group(function () {
    Route::post('/forecast/menus', 'getForcastMenus');
    Route::post('/forecast/menus/{menuId}', 'getForcastMenusByMenuId');
    Route::post('/forecast/hr', 'getForcastHR');
    Route::post('/forecast/hr/{menuId}', 'getForcastHrByMenuId');
    Route::post('/forecast/raw_materials/{menuId}', 'getForcastRawMaterialByMenuId');
    Route::post('/forecast/raw_materials', 'getForcastRawMaterial');

    Route::get('/forecasts/purchase_orders', 'getPoForecasts');
    Route::post('/forecasts/purchase_orders_item/{itemId}', 'storePoForecastsByItemId');

    Route::post('/forecasts', 'storeForecast');
    Route::get('/forecasts_monthly_menus', 'getMonthlyMenuForecasts');
    Route::get('/forecasts_monthly_menu/{mrpForecastId}', 'getMonthlyMenuForecastsById');
    Route::post('/forecasts_monthly_menu/{mrpForecastId}', 'updateMenuForecast');
    Route::delete('/forecasts_mrp_monthly_menu/{target_mrp_forecast_id}', 'deleteMenuForecast');

    //ktv forecasts
    Route::get('/forecasts/monthly/ktv_product_tree', 'getMonthlyKTVProductTreeForecasts');
    Route::post('/forecast/ktvs', 'getForecastKTV');
    Route::post('/forecast/ktvs/{entityId}', 'getForecastKTVByEntityId');
    Route::post('/forecast/ktvs_raw_materials', 'getForecastKTVRawMaterials');
    Route::post('/forecast/ktvs_raw_materials/{entityId}', 'getForecastKTVRawMaterialsByEntityId');
    Route::post('/forecast/ktvs_hr', 'getForecastKTVHr');
    Route::post('/forecast/ktvs_hr/{entityId}', 'getForecastKTVHrByEntityId');
    Route::delete('/mrp_forecasts/{mrp_forecast_id}', 'deleteMrpForecast');
  });
});
