<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\OffDayHrController;
use App\Http\Controllers\API\SalaryController;

Route::middleware('auth:api')->group(function () {
  Route::prefix('hr')->controller(OffDayHrController::class)->group(function () {
    Route::get('/off_days', 'getOffDays');
    Route::post('/off_days', 'createOffDay');
    Route::delete('/off_days/{dayInOffDayId}', 'deleteOffDay');
    Route::post('/public_holidays', 'createPublicHoliday');
  });
  Route::prefix('hr')->controller(LeaveController::class)->group(function () {
    Route::get('/leave_categories', 'getLeaveCategoryLists');
    Route::post('/leave_categories', 'createLeaveCategory');
    Route::post('/leave_allowances', 'createLeaveAllowance');
    Route::get('/leave_allowances', 'getLeaveAllowance');
    Route::delete('/leave_allowances/{id}', 'deleteLeaveAllowance');
    Route::post('/leaves', 'createLeave');
    Route::get('/leaves', 'getLeave');
    Route::post('/leaves/{id}', 'updateLeave');
    Route::delete('/leaves/{id}', 'deleteLeave');
    Route::get('/leave_totals_by_staff/{staffId}', 'getLeaveTotalByStaff');

    Route::post('/exit_categories', 'createExitCategory');
    Route::get('/exit_categories', 'getExitCategoryLists');
    Route::post('/exit_passes', 'createExitPass');
    Route::get('/exit_passes', 'getExitPass');
    Route::post('/exit_passes/{id}', 'updateExitPass');
    Route::delete('/exit_passes/{id}', 'deleteExitPass');
    Route::get('/exit_passes_by_staff/{staffId}', 'getExitPassByStaff');
    Route::get('/staff_lists_by_role/{roleId}/department/{departmentId}', 'getStaffListByRoleAndDepartment');
  });

  Route::prefix('hr')->controller(SalaryController::class)->group(function () {
    Route::get('/salary_allowances', 'getAllowances');
    Route::post('/salary_allowances', 'createAllowance');
    Route::post('/salary_setups', 'storeSalarySetUp');
    Route::get('/salary_setups', 'getSalarySetUp');
    Route::get('/salary_setups/{id}', 'getSalarySetUpById');
    Route::post('/salary_setups/{id}', 'updateSalarySetUp');
    Route::delete('/salary_setup/salary_allowances/{salaryAllowanceId}', 'deleteSalaryAllowance');
    Route::get('/salaries', 'getSalaries');
    Route::post('/salaries/{id}', 'updateBasicSalary');
    Route::get('/overtime_fees', 'getOvertimeFee');
    Route::post('/overtime_fees', 'createOvertimeFee');
    Route::get('/overtime_categories', 'getOvertimeCategories');
    Route::post('/overtime_categories', 'createOvertimeCategories');
    Route::post('/overtimes', 'createOvertime');
    Route::get('/overtimes', 'getOvertimes');
    Route::post('/approval/overtimes/{id}', 'setOvertimeApproval');
    Route::get('/mobile/overtimes/{staffId}', 'getMobileOvertimesByStaffId');

    Route::get('/salary_batches', 'getSalaryBatch');
    Route::post('/salary_batches', 'createSalaryBatch');
    Route::get('/salary_batches/{id}', 'getSalaryBatchById');
    Route::post('/salary_batches/{id}', 'updateSalaryBatch');
    Route::delete('/salary_batches/{id}', 'deleteSalaryBatch');
    Route::delete('/salary_batch_staffs/{id}', 'deleteSalaryBatchStaff');

    Route::get('/calculate_salary', 'calculateSalary');
    Route::get('/allowance_types', 'getAllowanceTypes');
    Route::post('/pay_slips', 'createPaySlip');
    Route::get('/pay_slips', 'getPaySlips');
  });
});
