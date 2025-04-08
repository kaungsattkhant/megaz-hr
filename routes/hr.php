<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\OffDayHrController;


Route::middleware('auth:api')->group(function () {
  Route::controller(OffDayHrController::class)->group(function () {
    Route::get('/hr/off_days', 'getOffDays');
    Route::post('/hr/off_days', 'createOffDay');
    Route::delete('/hr/off_days/{dayInOffDayId}', 'deleteOffDay');
  });
  Route::prefix('hr')->controller(LeaveController::class)->group(function () {
    Route::get('/leave_categories', 'getLeaveCategoryLists');
    Route::post('/leave_categories', 'createLeaveCategory');
    Route::post('/leave_allowances', 'createLeaveAllowance');
    Route::get('/leave_allowances', 'getLeaveAllowance');
    Route::post('/leaves', 'createLeave');
    Route::get('/leaves', 'getLeave');
  });
});
