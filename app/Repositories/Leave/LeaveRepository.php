<?php

namespace App\Repositories\Leave;

use App\Models\Leave;
use App\Models\Staff;
use App\Models\ExitCategory;
use App\Models\LeaveCategory;
use App\Models\LeaveAllowance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Events\LeaveUpdateNotificationRequest;
use App\Models\ExitPass;
use App\Services\LeaveService;

class LeaveRepository implements LeaveRepositoryInterface
{
  private LeaveService $leaveService;
  public function __construct(LeaveService $leaveService)
  {
    $this->leaveService = $leaveService;
  }
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
        $currentYear = date('Y');
        foreach ($leave_allowances as $leave_allowance) {

          $existingAllowance = LeaveAllowance::where('role_id', $leave_allowance['role_id'])
            ->where('leave_category_id', $leave_allowance['leave_category_id'])
            ->whereYear('created_at', $currentYear)
            ->exists();
          if ($existingAllowance) {
            return ResponseMessage('This leave allowance already exists for this role and category in ' . $currentYear, 422);
          }
        }
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
  public function deleteLeaveAllowance($id)
  {
    DB::beginTransaction();
    try {
      $leaveAllowance = LeaveAllowance::findOrFail($id);
      $leaveAllowance->delete();
      DB::commit();
      ResponseData($leaveAllowance);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function createLeave($data)
  {
    DB::beginTransaction();
    try {
      if (isset($data['image'])) {
        $imageData = $data['image'];
        $extension = $imageData->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $image_path = $imageData->storeAs('leaveImgs', $hashedName, 'public');
        $image_url = Storage::url($image_path);
      }
      // Assume isIncludeWeekends == 1
      $startDate = \Carbon\Carbon::parse($data['start_date']);
      $endDate = \Carbon\Carbon::parse($data['end_date']);
      $day = $startDate->diffInDays($endDate) + 1;

      $this->leaveService->checkLeaveAllowance($data, $day);
      $leave = Leave::create([
        'leave_category_id' => $data['leave_category_id'],
        'title' => $data['title'],
        'detail' => $data['detail'] ?? null,
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'day' => $day,
        'staff_id' => $data['staff_id'],
        'created_by' => UserData()->id,
        'status' => $data['status'],
        'confirmed_at' => $data['confirmed_at'] ?? null,
        'confirmed_by' => $data['confirmed_by'] ?? null,
        'image_path' => $image_path ?? null,
        'image_url' => $image_url ?? null,
        'isIncludeWeekends' => 1,
        'is_unpaid_leave' => $data['is_unpaid_leave'] ?? null,
      ]);
      DB::commit();
      ResponseData($leave);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function updateLeave($data, $id)
  {
    DB::beginTransaction();
    try {
      $leave = Leave::findOrFail($id);
      if (!$leave) {
        ResponseMessage('Leave not found', 404);
      }
      $wasConfirmed = isset($data['confirmed_at']) && $data['confirmed_at'] != $leave->confirmed_at;
      $wasCancelled = isset($data['cancelled_at']) && $data['cancelled_at'] != $leave->cancelled_at;
      $leave->update([
        'confirmed_at' => $wasConfirmed ? $data['confirmed_at'] : $leave->confirmed_at,
        'confirmed_by' => $wasConfirmed ? $data['confirmed_by'] : $leave->confirmed_by,
        'cancelled_at' => $wasCancelled ? $data['cancelled_at'] : $leave->cancelled_at,
        'cancelled_by' => $wasCancelled ? $data['cancelled_by'] : $leave->cancelled_by,
        'status' => $data['status'] ?? $leave->status,
        'is_unpaid_leave' => $data['is_unpaid_leave'] ?? null,
      ]);
      if ($wasConfirmed || $wasCancelled) {
        broadcast(new LeaveUpdateNotificationRequest($leave, $leave->staff_id));
      }

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
    $query = Leave::with('leaveCategory', 'staff.roles.leaveAllowances', 'confirmed_by', 'cancelled_by')
      ->orderByDesc('id');

    if ($request->has('staff_id')) {
      $query->where('staff_id', $request->input('staff_id'));
    }
    if ($request->has('status')) {
      $query->where('status', $request->input('status'));
    }
    $leaveRecords = $query->paginate(config('common.list_count'));
    $staffRemainingLeave = [];

    foreach ($leaveRecords as $leave) {
      dd($leave);
      $staffId = $leave->staff_id;
      if (!isset($staffRemainingLeave[$staffId])) {
        $staffRemainingLeave[$staffId] = [
          'total_leave_taken' => 0,
          'total_leave_allowance' => 0,
        ];

        $staffRoles = $leave->staff->roles;
        foreach ($staffRoles as $role) {
          $leaveAllowances = $role->leaveAllowances;
          foreach ($leaveAllowances as $leaveAllowance) {
            $staffRemainingLeave[$staffId]['total_leave_allowance'] += $leaveAllowance->day;
          }
        }
      }
      if ($leave->status !== 'cancelled') {
        $staffRemainingLeave[$staffId]['total_leave_taken'] += $leave->day;
      }
    }
    $leaveRecordsModified = $leaveRecords->map(function ($leave) use ($staffRemainingLeave) {
      $staffId = $leave->staff_id;
      $remainingLeave = $staffRemainingLeave[$staffId]['total_leave_allowance'] - $staffRemainingLeave[$staffId]['total_leave_taken'];
      $leave->total_remaining_leave_balance = $remainingLeave;

      return $leave;
    });
    $pagination = $leaveRecords->toArray();
    return [
      'leave_records' => $leaveRecordsModified,
      'pagination' => [
        'total' => $pagination['total'],
        'per_page' => $pagination['per_page'],
        'current_page' => $pagination['current_page'],
        'last_page' => $pagination['last_page'],
        'from' => $pagination['from'],
        'to' => $pagination['to'],
        'first_page_url' => $pagination['first_page_url'],
        'last_page_url' => $pagination['last_page_url'],
        'next_page_url' => $pagination['next_page_url'],
        'prev_page_url' => $pagination['prev_page_url'],
        'path' => $pagination['path'],
        'links' => $pagination['links'],
      ]
    ];
  }

  public function deleteLeave($id)
  {
    DB::beginTransaction();
    try {
      $leave = Leave::findOrFail($id);
      if ($leave->image_path && Storage::disk('public')->exists('leaveImgs/' . $leave->image_path)) {
        Storage::disk('public')->delete('leaveImgs/' . $leave->image_path);
      }
      $leave->delete();
      DB::commit();
      ResponseData($leave);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getLeaveTotalByStaff($staffId)
  {

    $staff = Staff::where('id', $staffId)->firstOrFail();
    $roles = $staff->roles;
    $leaveAllowances = LeaveAllowance::whereIn('role_id', $roles->pluck('id'))->get();
    $confirmedLeaveRecords = Leave::where('staff_id', $staffId)
      ->where('status', '!=', 'cancelled')
      ->whereNotNull('confirmed_at')
      ->get();
    $totalLeaveRecords = Leave::where('staff_id', $staffId)
      ->with(['leaveCategory', 'staff'])
      ->select('id', 'leave_category_id', 'title', 'start_date', 'end_date', 'staff_id', 'status')
      ->paginate(config('common.list_count'));
    $leaveTakenByCategory = [];

    foreach ($confirmedLeaveRecords as $leave) {

      if (!isset($leaveTakenByCategory[$leave->leave_category_id])) {
        $leaveTakenByCategory[$leave->leave_category_id] = 0;
      }
      $leaveTakenByCategory[$leave->leave_category_id] += $leave->day;
    }

    $totalLeaveAllowance = 0;
    $totalLeaveTaken = 0;

    foreach ($leaveAllowances as $leaveAllowance) {
      $leaveCategoryId = $leaveAllowance->leave_category_id;
      $totalLeaveAllowance += $leaveAllowance->day;

      $leaveTaken = isset($leaveTakenByCategory[$leaveCategoryId]) ? $leaveTakenByCategory[$leaveCategoryId] : 0;
      $remainingLeave = $leaveAllowance->day - $leaveTaken;

      $categoryName = LeaveCategory::find($leaveCategoryId)->name;

      $result[] = [
        'staff_id' => $staffId,
        'category_id' => $leaveCategoryId,
        'category_name' => $categoryName,
        'total_leave' => $leaveAllowance->day,
        'leave_taken' => $leaveTaken,
        'remaining_leave' => $remainingLeave,
      ];

      $totalLeaveTaken += $leaveTaken;
    }
    $totalLeaveRecordsModified = $totalLeaveRecords->map(function ($leaveRecord) {
      return [
        'id' => $leaveRecord->id,
        'leave_category_id' => $leaveRecord->leave_category_id,
        'leave_category_name' => $leaveRecord->leaveCategory->name,
        'title' => $leaveRecord->title,
        'start_date' => $leaveRecord->start_date,
        'end_date' => $leaveRecord->end_date,
        'staff_id' => $leaveRecord->staff_id,
        'staff_name' => $leaveRecord->staff->name,
        'status' => $leaveRecord->status,

      ];
    });
    $pagination = $totalLeaveRecords->toArray();
    return [
      'total_leave_allowance' => $totalLeaveAllowance,
      'total_leave_taken' => $totalLeaveTaken,
      'total_remaining_leave' => $totalLeaveAllowance - $totalLeaveTaken,
      'leave_details' => $result,
      'total_leave_records' => $totalLeaveRecordsModified,
      'pagination' => [
        'total' => $pagination['total'],
        'per_page' => $pagination['per_page'],
        'current_page' => $pagination['current_page'],
        'last_page' => $pagination['last_page'],
        'from' => $pagination['from'],
        'to' => $pagination['to'],
        'first_page_url' => $pagination['first_page_url'],
        'last_page_url' => $pagination['last_page_url'],
        'next_page_url' => $pagination['next_page_url'],
        'prev_page_url' => $pagination['prev_page_url'],
        'path' => $pagination['path'],
        'links' => $pagination['links'],
      ]
    ];
  }

  public function createExitCategory($request)
  {
    DB::beginTransaction();
    try {
      $exitCategory = new ExitCategory();
      $exitCategory->name = $request['name'];
      $exitCategory->save();
      DB::commit();
      ResponseData($exitCategory);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getExitCategoryLists($request)
  {
    return ExitCategory::orderByDesc('id')->paginate(config('common.list_count'));
  }

  public function createExitPass($request)
  {
    DB::beginTransaction();
    try {
      $exitPass = new ExitPass();
      $exitPass->exit_category_id = $request['exit_category_id'];
      $exitPass->staff_id = $request['staff_id'];
      $exitPass->detail = $request['detail'] ?? null;
      $exitPass->exit_date_time = $request['exit_date_time'];
      $exitPass->arrival_date_time = $request['arrival_date_time'];
      $exitPass->status = $request['status'];
      $exitPass->save();

      DB::commit();
      ResponseData($exitPass);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function getExitPass($request)
  {
    return  ExitPass::with(['exitCategory', 'staff.department', 'staff.roles'])
      ->orderByDesc('id')

      ->when($request->status, function ($query) use ($request) {
        $query->where('status', $request->status);
      })
      ->when($request->staff_id, function ($query) use ($request) {
        $query->where('staff_id', $request->staff_id);
      })
      ->paginate(config('common.list_count'));
  }

  public function updateExitPass($request, $id)
  {
    DB::beginTransaction();
    try {
      $exitPass = ExitPass::findOrFail($id);
      if (!$exitPass) {
        ResponseMessage('ExitPass not found', 404);
      }
      if (isset($request['status'])) {
        $exitPass->status = $request['status'];
        if ($request['status'] === 'arrival_received') {
          $exitPass->arrival_at = now();
        }
      }
      $exitPass->save();

      DB::commit();
      ResponseData($exitPass);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function deleteExitPass($id)
  {
    DB::beginTransaction();
    try {
      $exitPass = ExitPass::findOrFail($id);
      if (!$exitPass) {
        ResponseMessage('ExitPass not found', 404);
      }
      $exitPass->delete();
      DB::commit();
      ResponseData($exitPass);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getExitPassByStaff($staffId)
  {
    $staff = Staff::where('id', $staffId)->firstOrFail();
    $exitPasses = ExitPass::where('staff_id', $staff->id)
      ->with(['exitCategory'])
      ->orderByDesc('id')
      ->paginate(config('common.list_count'));
    if ($exitPasses->isEmpty()) {
      ResponseMessage('ExitPasses not found.', 404);
    }
    ResponseData($exitPasses);
  }
  public function getStaffListByRoleAndDepartment($roleId, $departmentId)
  {
    $staffs = Staff::whereHas('roles', function ($query) use ($roleId) {
      $query->where('id', $roleId);
    })
      ->where('department_id', $departmentId)
      ->with(['department', 'roles'])
      ->orderByDesc('id')
      ->paginate(config('common.list_count'));
    ResponseData($staffs);
  }
}
