<?php

namespace App\Repositories\Leave;

interface LeaveRepositoryInterface
{
  public function getLeaveCategoryLists($request);
  public function createLeaveCategory($data);
  public function createLeaveAllowance($data);
  public function getLeaveAllowance($request);
  public function createLeave($data);
  public function getLeave($request);
}
