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

    // First, check for a leave allowance specific to the staff member
    $allowance = LeaveAllowance::where('allowanceable_type', 'staff')
      ->where('allowanceable_id', $staff->id)
      ->where('leave_category_id', $leaveCategoryId)
      ->value('day');

    // If no staff-specific allowance is found, check for role-based allowances
    if ($allowance === null) {
      $roles = $staff->roles;
      if ($roles->isEmpty()) {
        ResponseMessage('Staff does not have any roles assigned and no specific leave allowance.', 422);
      }

      $roleAllowances = LeaveAllowance::where('allowanceable_type', 'role')
        ->whereIn('allowanceable_id', $roles->pluck('id'))
        ->where('leave_category_id', $leaveCategoryId)
        ->pluck('day');

      if ($roleAllowances->isEmpty()) {
        ResponseMessage('Leave allowance not found for the staff\'s roles and leave category.', 422);
      }

      // Use the maximum allowance from all assigned roles
      $allowance = $roleAllowances->max();
    }

    $totalTaken = Leave::where('staff_id', $staffId)
      ->where('leave_category_id', $leaveCategoryId)
      ->sum('day');

    $totalLeaveTakenByCategory = $totalTaken + $day;
    if ($totalLeaveTakenByCategory > $allowance) {
      ResponseMessage('You have exceeded your allowed leave days for this category.', 400);
    }
  }
}
