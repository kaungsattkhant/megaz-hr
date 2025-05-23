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
    $query = Staff::with(['emergencyContacts', 'skills', 'department', 'roles'])->where('is_cv', 1)->orderBy('id', 'desc');

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
      $this->createEmegercyContact($data);
      DB::commit();
      return $staff;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function createEmegercyContact($data)
  {
    $staff = StaffEmergencyContact::where('staff_id', $data['staff_id'])->first();
    if (!$staff) {
      $staff = StaffEmergencyContact::create($data);
    }
  }
}
