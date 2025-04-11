<?php

namespace App\Repositories\Leave;

interface LeaveRepositoryInterface
{
  public function getLeaveCategoryLists($request);
  public function createLeaveCategory($data);
  public function createLeaveAllowance($data);
  public function getLeaveAllowance($request);
  public function deleteLeaveAllowance($id);
  public function createLeave($data);
  public function getLeave($request);
  public function updateLeave($data, $id);
  public function deleteLeave($id);

  public function getLeaveTotalByStaff($staffId); //for mobile api

  public function getExitCategoryLists($request);
  public function createExitCategory($request);
  public function getExitPass($request);
  public function createExitPass($request);
  public function updateExitPass($request, $id);
  public function deleteExitPass($id);

  public function getExitPassByStaff($staffId); //mobile api

  public function getStaffListByRoleAndDepartment($roleId, $departmentId);
}
