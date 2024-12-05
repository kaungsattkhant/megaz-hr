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
    Route::post('/forecast/menus/{menuId}', 'getForcastMenus');
    Route::post('/forecast/hr/{menuId}', 'getForcastHR');
    Route::post('/forecast/raw_materials/{menuId}', 'getForcastRawMaterial');
    Route::post('/forecasts', 'storeForecast');
    Route::post('/forecasts/{mrpForecastId}', 'updateForecast');
    Route::get('/forecasts', 'getForecasts');
  });
});
