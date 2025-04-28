<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\Staff;
use App\Models\LeaveAllowance;

class LeaveService
{
  public function  checkLeaveAllowance($data, $day)
  {
    $leaveCategoryId = $data['leave_category_id'];
    $staffId = $data['staff_id'];
    $staff = Staff::findOrFail($staffId);
    $roles = $staff->roles;
    if ($roles->isEmpty()) {
      ResponseMessage('Staff does not have any roles assigned.', 422);
    }
    $allowance = 0;

    foreach ($roles as $role) {
      $allowanceForRole = LeaveAllowance::where('role_id', $role->id)
        ->where('leave_category_id', $leaveCategoryId)
        ->value('day');
      if ($allowanceForRole === null) {
        ResponseMessage('Leave allowance not found for this role and leave category.', 422);
      }

      $allowance = max($allowance, $allowanceForRole);
    }
    $totalTaken = Leave::where('staff_id', $staffId)
      ->where('leave_category_id', $leaveCategoryId)
      // ->where('status', 'confirmed')
      ->sum('day');

    $totalLeaveTakenByCategory = $totalTaken + $day;
    if ($totalLeaveTakenByCategory > $allowance) {
      ResponseMessage('You have exceeded your allowed leave days for this category.', 400);
    }
  }
}
