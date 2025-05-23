<?php

namespace App\Repositories\Cv;

use App\Models\Skill;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use App\Models\StaffEmergencyContact;

class CvRepository implements CvRepositoryInterface
{
  public function skillByRoleAndDepartment($depId, $roleId)
  {
    $skills = Skill::whereHas('role', function ($query) use ($roleId, $depId) {
      $query->where('department_id', $depId)
        ->where('id', $roleId);
    })->orderBy('id', 'desc')->paginate(config('common.list_count'));
    return $skills;
  }
  public function getAllCvs($request)
  {
    // $query = Staff::with(['emergencyContacts', 'skills', 'department', 'roles'])->where('is_cv', 1)->orderBy('id', 'desc');
    $query = Staff::with(['emergencyContacts', 'skills', 'department', 'roles'])
      ->where('is_cv', 1)
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
      $data['is_cv'] = 1;
      $data['is_active'] = 1;
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

      if (isset($data['status']) && $data['status'] === "cancelled") {
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
}
