<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OffDayHrController;


Route::middleware('auth:api')->group(function () {
  Route::controller(OffDayHrController::class)->group(function () {
    Route::get('/hr/off_days', 'getOffDays');
    Route::post('/hr/off_days', 'createOffDay');
    Route::delete('/hr/off_days/{dayInOffDayId}', 'deleteOffDay');
  });
});
