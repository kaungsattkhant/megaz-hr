<?php

namespace App\Repositories\Resignation;

use Exception;
use App\Models\Staff;
use App\Models\Resignation;
use App\Models\ResignCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ResignationRepository implements ResignationRepositoryInterface
{

  public function getResignationCategoryLists()
  {
    return ResignCategory::orderBy('id', 'desc')->get();
  }
  public function createResignationCategory(array $data)
  {
    return ResignCategory::create($data);
  }
  public function getAllResignations()
  {
    return Resignation::with(['staff', 'resignCategory'])->orderBy('id', 'desc')->paginate(config('common.list_count'));
  }

  public function getResignationById($id)
  {
    try {
      return Resignation::with(['staff', 'resignCategory'])->findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return ResponseMessage('Resignation not found', 404);
    }
  }

  public function createResignation(array $validatedData)
  {
    DB::beginTransaction();
    try {
      if (isset($validatedData['staff_id'])) {
        $staff = Staff::find($validatedData['staff_id']);
        if ($staff && $staff->is_active === 0) {
          ResponseMessage("The staff member is already resigned.", 400);
        }
        $validatedData['staff_id'] = $validatedData['staff_id'] ?? null;
      }
      if (isset($validatedData['image'])) {
        $imageData = $validatedData['image'];
        $extension = $imageData->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $validatedData['image_path'] = $imageData->storeAs('resignImgs', $hashedName, 'public');
        $validatedData['image_url'] = Storage::url($validatedData['image_path']);
      }
      if (isset($validatedData['status']) && $validatedData['status'] === "confirmed") {
        $validatedData['confirmed_by'] = $validatedData['confirmed_by'] ?? null;
        $validatedData['confirmed_at'] = now();
      }

      if (isset($validatedData['status']) && $validatedData['status'] === "cancelled") {
        $validatedData['cancelled_by'] = $validatedData['cancelled_by'] ?? null;
        $validatedData['cancelled_at'] = now();
      }

      if (isset($validatedData['status']) && $validatedData['status'] === "received") {
        unset($validatedData['confirmed_by']);
        unset($validatedData['cancelled_by']);
      }

      $resignationData = Resignation::create($validatedData);

      if ($resignationData->status === "confirmed") {
        $resignationData->staff->update(['is_active' => 0]);
      }

      DB::commit();
      ResponseData($resignationData);
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
  public function getResignationByStaffId($staffId)
  {
    try {
      return Resignation::where('staff_id', $staffId)->first();
    } catch (ModelNotFoundException $e) {
      return ResponseMessage('Resignation not found', 404);
    }
  }
  public function updateResignation(array $data, $id)
  {
    DB::beginTransaction();
    try {
      $resignation = $this->getResignationById($id);
      if (!$resignation) {
        ResponseMessage('Resignation not found', 404);
      }
      if (isset($data['status']) && $data['status'] === "confirmed") {
        $resignation->status = $data['status'];
        $resignation->confirmed_by = $data['confirmed_by'] ?? null;
        $resignation->confirmed_at = now();
        $resignation->staff->update(['is_active' => 0]);
      }

      if (isset($data['status']) && $data['status'] === "cancelled") {
        $resignation->status = $data['status'];
        $resignation->cancelled_by = $data['cancelled_by'] ?? null;
        $resignation->cancelled_at = now();
        $resignation->staff->update(['is_active' => 1]);
      }
      $resignation->update($data);
      DB::commit();
      ResponseData($resignation);
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
