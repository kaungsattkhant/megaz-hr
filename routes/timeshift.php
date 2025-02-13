<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\ParticipantNotificationController;
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
    Route::post('/po_arrival_items', 'storePoArrivalItems');
    Route::get('/supplier_lead_time/{supplierId}', 'getSupplierLeadTime');
    Route::get('/invoices', 'getInvoices');
    Route::post('/invoices', 'storeInvoices');
  });

  Route::controller(ParticipantNotificationController::class)->group(function () {
    Route::get('/staff_by_department/{departmentId}/role/{roleId}', 'getStaffByDepartmentRole');
    Route::post('/meetings', 'storeMeetings');
    Route::get('/meetings/{meetingId}', 'showMeeting');
    Route::get('/meetings', 'getMeetings');
    Route::post('/meetings/{meetingId}', 'updateMeeting');
    Route::delete('/meetings/{meetingId}', 'deleteMeeting');

    Route::get('/trainings', 'getTrainings');
    Route::get('/trainings/{trainingId}', 'getTrainingById');
    Route::post('/trainings', 'storeTraining');
    Route::post('/trainings/{trainingId}', 'updateTraining');
    Route::delete('/trainings/{trainingId}', 'deleteTraining');

    Route::get('/org_news', 'getOrgNews');
    Route::get('/org_news/{orgNewsId}', 'getOrgNewsById');
    Route::post('/org_news', 'storeOrgNews');
    Route::post('/org_news/{orgNewsId}', 'updateOrgNews');
    Route::delete('/org_news/{orgNewsId}', 'deleteOrgNews');

    Route::get('/warnings', 'getWarnings');
    Route::get('/warnings/{warningId}', 'getWarningById');
    Route::post('/warnings', 'storeWarning');
    Route::post('/warnings/{warningId}', 'updateWarning');
    Route::delete('/warnings/{warningId}', 'deleteWarning');
  });
});
