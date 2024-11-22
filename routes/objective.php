<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ObjectiveController;

Route::middleware('auth:api')->group(function () {
  Route::controller(ObjectiveController::class)->group(function () {
    Route::get('/objectives', 'getObjectives');
    Route::get('/roles_department/{id}', 'getRolesByDepartmentId');
    Route::post('/objectives', 'store');
    Route::post('/objectives/{id}', 'update');
    Route::get('/objectives/{id}', 'getObjectiveById');
    Route::get('/objectives/{id}', 'getObjectiveById');
    Route::delete('/objectives/{id}', 'deleteObjective');

    //mobile-api
    Route::get('/daily/objectives', 'objectiveLists');
    Route::get('/daily/objectives_key', 'getdailyObjectives');
    Route::post('/daily/objectives_key_staff/{id}', 'updateDailyObjective');
    Route::post('/objectives/images', 'storeImages');
    Route::post('/objectives/images/{objKeyImgId}', 'updateImages');
  });
});
