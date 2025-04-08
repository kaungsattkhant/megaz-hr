<?php

namespace App\Repositories\Leave;

use App\Models\Leave;
use App\Models\LeaveCategory;
use App\Models\LeaveAllowance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveRepository implements LeaveRepositoryInterface
{
  public function getLeaveCategoryLists($request)
  {
    return LeaveCategory::orderByDesc('id')->paginate(config('common.list_count'));
  }
  public function createLeaveCategory($data)
  {
    DB::beginTransaction();
    try {
      $leaveCategory = new LeaveCategory();
      $leaveCategory->name = $data['name'];
      $leaveCategory->is_active = $data['is_active'] ?? true;
      $leaveCategory->save();
      DB::commit();
      ResponseData($leaveCategory);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }


  public function createLeaveAllowance($data)
  {
    DB::beginTransaction();
    try {
      if (isset($data['leave_allowances'])) {
        $leave_allowances  = json_decode($data['leave_allowances'], true);
        foreach ($leave_allowances as $leave_allowance) {
          $leave_allowance = LeaveAllowance::create([
            'role_id' => $leave_allowance['role_id'],
            'leave_category_id' => $leave_allowance['leave_category_id'],
            'day' => $leave_allowance['day'],
            'created_by' => UserData()->id,
          ]);
        }
      } else {
        ResponseMessage('Leave allowances is required', 422);
      }
      DB::commit();
      ResponseData($leave_allowance);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getLeaveAllowance($request)
  {
    $query = LeaveAllowance::with('role.department', 'leaveCategory')
      ->orderByDesc('id');

    if ($request->has('department_id')) {
      $query->whereHas('role.department', function ($query) use ($request) {
        $query->where('id', $request->input('department_id'));
      });
    }
    if ($request->has('role_id')) {
      $query->where('role_id', $request->input('role_id'));
    }
    return $query->paginate(config('common.list_count'));
  }

  public function createLeave($data)
  {
    DB::beginTransaction();
    try {
      if (isset($data['image'])) {
        $imageData = $data['image'];
        $extension = $imageData->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $data['image_path'] = $imageData->storeAs('leaveImgs', $hashedName, 'public');
        $data['image_url'] = Storage::url($data['image_path']);
      }
      $leave = Leave::create([
        'leave_category_id' => $data['leave_category_id'],
        'title' => $data['title'],
        'detail' => $data['detail'] ?? null,
        'start_date' => $data['start_date'],
        'day' => $data['day'],
        'staff_id' => $data['staff_id'],
        'created_by' => UserData()->id,
        'status' => $data['status'],
        'confirmed_at' => $data['confirmed_at'] ?? null,
        'confirmed_by' => $data['confirmed_by'] ?? null,
        'image_path' => $data['image_path'] ?? null,
        'image_url' => $data['image_url'] ?? null,
      ]);
      DB::commit();
      ResponseData($leave);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getLeave($request)
  {
    $query = Leave::with('leaveCategory', 'Staff', 'created_by', 'confirmed_by', 'cancelled_by')
      ->orderByDesc('id');

    if ($request->has('staff_id')) {
      $query->where('staff_id', $request->input('staff_id'));
    }
    if ($request->has('status')) {
      $query->where('status', $request->input('status'));
    }
    return $query->paginate(config('common.list_count'));
  }
}
