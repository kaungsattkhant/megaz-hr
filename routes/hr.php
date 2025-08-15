<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CvController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\SalaryController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\OffDayHrController;
use App\Http\Controllers\API\HandBookeController;
use App\Http\Controllers\API\InterviewController;
use App\Http\Controllers\API\ResignationController;
use App\Http\Controllers\API\StaffTimeShiftController;
use App\Http\Controllers\API\AssetItemEquipmentAssignController;

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
    Route::delete('/overtime_fees/{id}', 'deleteOvertimeFee');
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
    Route::delete('/pay_slips/{id}', 'deletePaySlip');

    Route::get('/export-salary', 'exportSalary');
  });
  Route::prefix('hr')->controller(ResignationController::class)->group(function () {
    Route::get('/resignation_categories', 'getResignationCategoryLists');
    Route::post('/resignation_categories', 'createResignationCategory');
    Route::post('/resignations', 'createResignation');
    Route::get('/resignations', 'getAllResignations');
    Route::get('/resignations/{id}', 'getResignationById');

    Route::post('/resignations/{id}', 'updateResignation');
    Route::get('/resignations-by-staff/{staffId}', 'getResignationByStaffId');
  });
  Route::prefix('hr')->controller(CvController::class)->group(function () {
    Route::get('/cvs', 'getAllCvs');
    Route::post('/cvs/{id}', 'updateCv');
    Route::delete('/cvs/{id}', 'deleteCv');
    Route::get('departments/{depId}/roles/{role_id}/skills', 'skillByRoleAndDepartment');
    Route::get('/cvs/{id}', 'getCvById');
    Route::post('/cvs/{id}/status', 'updateCvStatus');
    Route::get('/salary_setup/department/{departmentId}/role/{roleId}', 'getSalarySetupByDepartmentIdAndRoleId');
    Route::post('/new-staff-salary', 'createNewStaffSalary');
    Route::post('/new-staff-join-date', 'storeNewStaffJoinDate');
  });
  Route::prefix('hr')->controller(ExamController::class)->group(function () {
    Route::get('/exams', 'getAllExams');
    Route::post('/exams', 'createExam');
    Route::post('/exams/{id}', 'updateExam');
    Route::delete('/exams/{id}', 'deleteExam');
    Route::get('/exams/{id}', 'getExamById');
    Route::delete('/exam_skills/{id}', 'deleteExamSkill');
    Route::delete('/grades/{id}', 'deleteGrade');
    Route::delete('/exam_questions/{id}', 'deleteExamQuestion');
    Route::post('toggle/exam_questions/{id}', 'toggleExamQuestion');
  });
  Route::prefix('hr')->controller(InterviewController::class)->group(function () {
    Route::get('/interviews-by-role/{roleId}', 'getInterviewsByRoleId');
    Route::get('/interviews/{id}', 'getInterviewById');
    Route::post('/interviews', 'storeInterview');
    Route::get('/interviews-results', 'getInterviewResults');
  });
  Route::prefix('hr')->controller(LocationController::class)->group(function () {
    Route::get('/locations', 'getAllLocations');
    Route::post('/locations', 'createLocation');
    Route::get('/locations/{locationId}/floor/{floorId}', 'getPlaceByLocationAndFloorId');
    Route::post('/places/{placeId}/assign-staff', 'assignStaffToPlace');
  });
  Route::prefix('hr')->controller(StaffTimeShiftController::class)->group(function () {
    Route::post('/staff_time_shifts', 'createStaffTimeShift');
    Route::get('/staff_time_shifts', 'getStaffTimeShifts');
    Route::post('/staff_time_shifts/{id}/status', 'updateStaffTimeShiftStatus');
  });
  Route::prefix('hr')->controller(AssetItemEquipmentAssignController::class)->group(function () {
    Route::post('/asset-assignments', 'createAssetAssign');
    Route::get('/asset-assignments', 'getAssetAssigns');
    Route::post('/equipment-assignments', 'createEquipmentAssign');
    Route::get('/equipment-assignments', 'getEquipmentAssigns');
  });

  Route::prefix('hr')->controller(HandBookeController::class)->group(function () {
    Route::post('/hand-books', 'updateOrCreateHandBook');
    Route::get('/hand-books', 'getHandBookList');
    Route::get('/hand-books/{id}', 'getHandBookById');
    Route::delete('/hand-books/{id}', 'deleteHandBook');
  });
});
Route::prefix('hr')->controller(CvController::class)->group(function () {
  Route::post('/cvs', 'createCv');
});
