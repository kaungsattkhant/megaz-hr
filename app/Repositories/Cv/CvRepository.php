<?php

namespace App\Repositories\Cv;

use App\Enums\StaffStatus;
use App\Models\Skill;
use App\Models\Staff;
use App\Models\Salary;
use App\Models\SalarySetup;
use App\Models\SalaryAllowance;
use Illuminate\Support\Facades\DB;
use App\Models\StaffEmergencyContact;
use App\Http\Action\SendNotification\SendNotification;

class CvRepository implements CvRepositoryInterface
{
  use SendNotification;
  public function skillByRoleAndDepartment($depId, $roleId)
  {
    $skills = Skill::whereHas('role', function ($query) use ($roleId, $depId) {
      $query->where('department_id', $depId)
        ->where('id', $roleId);
    })
      ->orderBy('id', 'desc')
      ->paginate(config('common.list_count'));
    return $skills;
  }
  public function getAllCvs($request)
  {
    $query = Staff::with(['emergencyContacts', 'skills', 'department', 'roles'])
      // ->where('is_cv', 1)
      ->whereIn('status', [StaffStatus::APPLIED->value])
      ->orderBy('created_at', 'desc');
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    if ($request->has('skills') && is_array($request->skills)) {
      $query->whereHas('skills', function ($q) use ($request) {
        $q->whereIn('skill_id', $request->skills);
      });
    }
    if ($request->has('search_input')) {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'LIKE', '%' . $request->search_input . '%')
          ->orWhere('email', 'LIKE', '%' . $request->search_input . '%')
          ->orWhere('phone_number', 'LIKE', '%' . $request->search_input . '%')
          ->orWhere('status', 'LIKE', '%' . $request->search_input . '%');
      });
    }
    if ($request->has('roleIds') && is_array($request->roleIds)) {
      $query->whereHas('roles', function ($q) use ($request) {
        $q->whereIn('id', $request->roleIds);
      });
    }
    if ($request->has('departmentIds') && is_array($request->departmentIds)) {
      $query->whereIn('department_id', $request->departmentIds);
    }
    return $query->paginate(config('common.list_count'));
  }

  public function createCv(array $data)
  {
    DB::beginTransaction();
    try {
      // $data['is_cv'] = 1;
      // $data['is_active'] = 1;
      $data['status'] = StaffStatus::APPLIED->value;
      $data = RemoveNullValues($data);
      $staff = Staff::create($data);
      if (isset($data['skills'])) {
        $staff->skills()->sync($data['skills']);
      }

      if (isset($data['roles']) && is_array($data['roles'])) {
        $staff->roles()->sync($data['roles']);
      }

      $data['staff_id'] = $staff->id;

      $staff->emergencyContacts()->updateOrCreate(['staff_id' => $staff->id], $data);
      DB::commit();
      return $staff;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function getCvById($id)
  {
    $staff = Staff::with(['emergencyContacts', 'skills', 'department', 'roles'])->where('is_cv', 1)->find($id);
    if (!$staff) {
      ResponseMessage('CV not found with given ID', 404);
    }
    return $staff;
  }
  public function updateCv(int $id, array $data)
  {
    DB::beginTransaction();
    try {
      $staff = Staff::find($id);
      if (!$staff) {
        ResponseMessage('Staff not found with given ID', 404);
      }
      $data['is_cv'] = 1;
      $data['status'] = StaffStatus::APPLIED->value;
      $data['is_active'] = 1;
      $data = RemoveNullValues($data);
      $staff->update($data);
      if (isset($data['skills'])) {
        $staff->skills()->sync($data['skills']);
      }

      if (isset($data['roles']) && is_array($data['roles'])) {
        $staff->roles()->sync($data['roles']);
      }
      if (isset($data['nrc_front_path'])) {
        if ($staff->nrc_front_path) {
          DeleteFileFromServer($staff->nrc_front_path);
        }
      }

      if (isset($data['nrc_back_path'])) {
        if ($staff->nrc_back_path) {
          DeleteFileFromServer($staff->nrc_back_path);
        }
      }

      if (isset($data['household_registration_path'])) {
        if ($staff->household_registration_path) {
          DeleteFileFromServer($staff->household_registration_path);
        }
      }

      if (isset($data['profile_image_path']) && $staff->profile_image_path) {
        DeleteFileFromServer($staff->profile_image_path);
      }

      $staff->update($data);
      $staff->emergencyContacts()->updateOrCreate(['staff_id' => $staff->id], $data);
      DB::commit();
      return $staff;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
    }
  }

  public function deleteCv($id)
  {
    DB::beginTransaction();
    try {
      $staff = Staff::find($id);
      if (!$staff) {
        ResponseMessage('Staff not found with given ID', 404);
      }
      $staff->skills()->detach();
      $staff->roles()->detach();

      if ($staff->nrc_front_path) {
        DeleteFileFromServer($staff->nrc_front_path);
      }
      if ($staff->nrc_back_path) {
        DeleteFileFromServer($staff->nrc_back_path);
      }
      if ($staff->household_registration_path) {
        DeleteFileFromServer($staff->household_registration_path);
      }
      $staff->emergencyContacts()->delete();
      $staff->delete();
      DB::commit();
      return $staff;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
    }
  }
  public function updateCvStatus($id, array $data)
  {
    DB::beginTransaction();
    try {
      $staff = Staff::find($id);
      if (!$staff) {
        ResponseMessage('Staff not found with given ID', 404);
      }
      if (isset($data['status']) && $data['status'] === "pending") {
        $staff->status = $data['status'];
        $staff->confirmed_by = null;
        $staff->confirmed_at = null;
        $staff->cancelled_by = null;
        $staff->cancelled_at = null;
      }
      if (isset($data['status']) && $data['status'] === "confirmed") {
        $staff->status = $data['status'];
        $staff->confirmed_by = $data['confirmed_by'] ?? null;
        $staff->confirmed_at = now();
      }
      if (isset($data['status']) && $data['status'] === 'cancelled') {
        $staff->status = $data['status'];
        $staff->cancelled_by = $data['cancelled_by'] ?? null;
        $staff->cancelled_at = now();
      }
      if (isset($data['status']) && $data['status'] === StaffStatus::SHORTLISTED->value) {
        $staff->status = $data['status'];
        $staff->confirmed_by = $data['confirmed_by'] ?? null;
        $staff->confirmed_at = now();
      }

      if (isset($data['status']) && $data['status'] === StaffStatus::REJECTED->vaue) {
        $staff->status = $data['status'];
        $staff->cancelled_by = $data['cancelled_by'] ?? null;
        $staff->cancelled_at = now();
      }
      $staff->update($data);
      DB::commit();
      return $staff;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
    }
  }

  public function getSalarySetupByDepartmentIdAndRoleId($departmentId, $roleId)
  {
    $salarySetup = SalarySetup::with('salaryAllowances.allowance', 'role.department')->where('role_id', $roleId)
      ->whereHas('role.department', function ($query) use ($departmentId) {
        $query->where('id', $departmentId);
      })
      ->first();
    return $salarySetup;
  }

  public function createNewStaffSalary(array $data)
  {
    DB::beginTransaction();
    try {
      $data['created_by'] = UserData()->id;
      $roleIds = Staff::find($data['staff_id'])->roles()->pluck('roles.id');

      $roleId = $roleIds->first();
      $salarySetup = SalarySetup::where('role_id', $roleId)->first();

      if ($data['basic_salary'] > $salarySetup->basic_salary) {
        ResponseMessage('Basic salary is greater than salary setup basic salary', 402);
      }

      $salary = Salary::updateOrCreate(
        [
          'staff_id' => $data['staff_id'],
          'salary_setup_id' => $data['salary_setup_id'],
        ],
        $data
      );

      if (isset($data['salary_allowances'])) {
        $salary_allowances = json_decode($data['salary_allowances'], true);
        if (!is_array($salary_allowances)) {
          return ResponseMessage('Invalid JSON format for salary allowances.', 400);
        }
        foreach ($salary_allowances as $salary_allowance) {
          SalaryAllowance::updateOrCreate(
            [
              'salary_setup_id' => $data['salary_setup_id'],
              'allowance_id' => $salary_allowance['allowance_id'],
            ],
            [
              'amount' => $salary_allowance['amount'],
            ]
          );
        }
      }
      DB::commit();
      return $salary;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
    }
  }
  public function storeNewStaffJoinDate(array $data)
  {
    DB::beginTransaction();
    try {
      $data['created_by'] = UserData()->id;
      $staff = Staff::find($data['staff_id']);
      // if (!$staff || $staff->status !== 'confirmed') {
      if (!$staff || $staff->status !== StaffStatus::HIRED->value) {
        ResponseMessage('Staff must be confirmed before assigning a join date.', 403);
      }
      $staff = Staff::updateOrCreate(
        [
          'id' => $data['staff_id'],
        ],
        [
          'joined_date' => $data['joined_date'],
          'probation_period' => $data['probation_period'],
          'status' => StaffStatus::PROBATION->value,
        ]
      );
      // $this->sendStaffJoinNotification($staff);
      DB::commit();
      return $staff;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
    }
  }
}
