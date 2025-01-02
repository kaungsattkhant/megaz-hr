<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TimeShiftController;

Route::middleware('auth:api')->group(function () {
  Route::controller(TimeShiftController::class)->group(function () {
    Route::get('/gps', 'getGPS');
    Route::get('/gps/{id}', 'getGPSById');
    Route::post('/gps/{id}', 'updateGPSById');

    Route::get('/shifts', 'getShifts');
    Route::get('/shifts/{id}', 'getShiftsById');
    Route::post('/shifts', 'storeShifts');

    Route::get('/time_shifts', 'getTimeShift');
    Route::get('/time_shifts/{id}', 'getTimeShiftById');
    Route::post('/time_shifts', 'storeTimeShift');
    Route::post('/time_shifts/{id}', 'updateTimeShift');
    Route::delete('/time_shifts/{id}', 'deleteTimeShiftById');
  });
});
