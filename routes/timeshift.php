<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\PoOrderController;
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

    //mobile check in out
    Route::get('/current_time_shifts', 'getCurrentTimeShift');
    Route::post('/check_ins', 'checkIn');
    Route::post('/check_ins/{id}', 'checkOut');

    //admin panel check in
    Route::get('/check_ins', 'getAllCheckIns');
    Route::get('/total_hours_check_ins', 'getTotalHoursCheckIns');
  });

  Route::controller(ContactController::class)->group(function () {
    Route::get('/contacts', 'contactList');
    Route::get('/contacts/{contactId}', 'getContactById');
    Route::post('/contacts', 'updateOrCreate');
    Route::delete('/contacts/{id}', 'delete');
  });


  Route::controller(PoOrderController::class)->group(function () {
    Route::get('/po_items', 'getPoOrderItems');
    Route::post('/po_items', 'storePoOrderItems');
    Route::get('/po_arrival_list', 'getPoOrderArrivalList');
    Route::get('/po_arrival_list/{itemId}', 'getPoOrderArrivalListByItemId');
    Route::get('/invoice_by_supplier/{supplierId}', 'getInvoiceBySupplier');
  });
});
