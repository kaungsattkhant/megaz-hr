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

  public function getLeaveTotalByStaff($staffId);
  public function getLeaveDetailsByStaff($staffId);
}
