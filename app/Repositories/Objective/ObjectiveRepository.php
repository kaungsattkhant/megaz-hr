<?php

namespace App\Repositories\Objective;

use Exception;
use App\Models\Item;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Entity;
use App\Models\KtvItem;
use App\Models\Objective;
use App\Models\KtvObjective;
use App\Models\ObjectiveKey;
use Illuminate\Http\Request;
use App\Models\KtvProductTree;
use App\Models\ObjectiveStaff;
use App\Models\ObjectiveAssign;
use App\Models\ObjectiveKeyDuty;
use App\Models\ObjectivekeyStaff;
use Illuminate\Support\Facades\DB;
use App\Models\ObjectiveStaffImage;
use Illuminate\Support\Facades\Auth;
use App\Models\CompletedObjectiveKey;
use App\Http\Resources\AssignResource;
use App\Models\ObjectiveKeyStaffImage;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ObjectiveResource;
use App\Http\Resources\KtvObjectiveRsource;
use App\Http\Resources\dailyObjectiveByStaffId;
use App\Http\Resources\DailyObjKeyStaffResource;
use App\Http\Resources\KtvProductTreeEditResource;

class ObjectiveRepository implements ObjectiveInterface
{

    public function dashboardOkr($request)
    {
        $from_date = isset($request->from_date) ? convertDateFormat($request->from_date) : null;
        $to_date = isset($request->to_date) ? convertDateFormat($request->to_date) : null;
        $staffId = $request->staff_id;
        $departmentId = $request->department_id;

        $okrDashboard = ObjectiveStaff::join('objective_key_duties', 'objectivekey_staff.objective_key_duty_id', 'objective_key_duties.id')
            ->join('staff', 'objectivekey_staff.staff_id', 'staff.id')
            ->join('departments', 'staff.department_id', 'departments.id')
            ->join('objective_keys', 'objectivekey_staff.objective_key_id', 'objective_keys.id')
            ->select(
                'objective_key_duties.assign_date',
                'objective_key_duties.due_date',
                'staff.name',
                'objective_keys.name as objective_key_name',
                'departments.name as department_name',
                DB::raw('SUM(objective_keys.okr_point) as okr_total_point'),
            )
            ->when($departmentId, function ($q) use ($departmentId) {
                $q->where('departments.id', $departmentId);
            })
            ->when($staffId, function ($q) use ($staffId) {
                $q->where('staff.id', $staffId);
            })
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(objective_key_duties.assign_date)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('objective_key_duties.assign_date', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('objective_key_duties.assign_date', [now(), $to_date]);
            })
            ->where('objectivekey_staff.status', 'approved')
            // ->whereNotNull('objectivekey_staff.completed_at')
            ->groupBy(
                'objectivekey_staff.staff_id',
                'objectivekey_staff.objective_key_id',
                'objective_key_duties.assign_date',
                'objective_key_duties.due_date',
            )
            ->paginate(20);
        return $okrDashboard;
        // ->where('status')

        // return Objec
    }

    public function getObjectives(Request $request)
    {
        $search = $request->input('search');
        $roleId = $request->input('roleId');
        $type = $request->input('type');

        $query = Objective::with(['role.department', 'sop', 'objectiveKeys'])
            ->objectiveFilter($search, $roleId, $type)->orderBy('id', 'desc');
        if ($request->per_page || $request->page) {
            return $query->orderBy('id', 'desc')->paginate(config('common.list_count'));
        } else {
            return $query->orderBy('id', 'desc')->get();
        }
    }
    public function getRolesByDepartmentId(Request $request, $departmentId)
    {
        return Role::with('department')->where('department_id', $departmentId)->get();
    }

    public function getObjectiveById(Request $request, $objId)
    {
        return Objective::with(['role.department', 'sop', 'objectiveKeys'])->where('id', $objId)->get();
    }

    public function deleteObjective($objId)
    {
        $objective = Objective::with('objectiveKeys')->findOrFail($objId);
        $objective->objectiveKeys()->delete();
        $objective->delete();

        ResponseMessage("Delete successfully", 200);
    }

    public function store(array $validatedData)
    {
        try {
            DB::beginTransaction();
            $validatedData['created_by'] = UserData()->id;
            $objective = Objective::updateOrCreate(
                ['id' => $validatedData['id'] ?? null],
                $validatedData
            );
            if (isset($validatedData['objective_key'])) {
                $objectiveKeys = json_decode($validatedData['objective_key'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for objective keys.', 400);
                }
                $updatedObjectiveKeys = [];
                foreach ($objectiveKeys as $objectiveKey) {
                    $updatedObjectiveKeys[] = $objectiveKey['id'] ?? null;
                }

                $existingObjectiveKeys = ObjectiveKey::where('objective_id', $objective->id)->pluck('id')->toArray();
                $objKeyToDelete = array_diff($existingObjectiveKeys, $updatedObjectiveKeys);
                if (!empty($objKeyToDelete)) {
                    ObjectiveKey::where('objective_id', $objective->id)->whereIn('id', $objKeyToDelete)->delete();
                }

                foreach ($objectiveKeys as $objectiveKey) {

                    ObjectiveKey::updateOrCreate(
                        [
                            'id' => $objectiveKey['id'] ?? null,
                        ],
                        [
                            'objective_id' => $objective->id,
                            'name' => $objectiveKey['name'],
                        ]
                    );
                }
            }
            DB::commit();
            return $objective;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    //assign duties keyresults
    public function getObjectiveKeysByStaffId(int $staffId)
    {
        $staff = Staff::findOrFail($staffId);
        $roleIds = $staff->roles->pluck('id');
        return ObjectiveStaff::with([
            'objective.objectiveKeys',
            'staff.roles',
            'staff.department',
            'objective.sop',
            'objective.role'
        ])->where('staff_id', $staffId)->orderBy('id', 'desc')->paginate(config('common.list_count'));
    }

    public function storeAssignDutiesByObjectives($validatedData)
    {
        DB::beginTransaction();
        try {
            if (isset($validatedData['okr_assign'])) {
                $okrAssigns = json_decode($validatedData['okr_assign'], true);
                if (!is_array($okrAssigns)) {
                    return ResponseMessage('Invalid JSON format for OKR assigns.', 400);
                }
                // if (isset($validatedData['del_ids'])) {
                //     $delIds = json_decode($validatedData['del_ids'], true);
                //     if (!is_array($delIds)) {
                //         return ResponseMessage('Invalid JSON format for delete IDs.', 400);
                //     }
                //     ObjectiveStaff::whereIn('id', $delIds)->delete();
                // }
                foreach ($okrAssigns as $okrAssign) {

                    $objective = Objective::findOrFail($okrAssign['objective_id']);
                    $objectiveAssign = ObjectiveAssign::firstOrCreate([
                        'objective_id' => $okrAssign['objective_id'],
                        'staff_id' => $okrAssign['staff_id']
                    ]);

                    $objectiveStaff = ObjectiveStaff::updateOrCreate(
                        [
                            'objective_assign_id' => $objectiveAssign->id,
                        ],
                        [
                            'start_date' => $okrAssign['start_date'],
                            'end_date' => $okrAssign['end_date'],
                            'okr_point' => $objective->okr_point,
                        ]
                    );
                }
            }
            DB::commit();
            return $objectiveStaff;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function getOkrAssigns(Request $request)
    {
        $query = ObjectiveAssign::with([
            'objectiveStaff',
            'staff.department',
            'staff.roles',
            'objective.objectiveKeys',
            'objective.sop'
        ]);
        if ($request->has('role_id')) {
            $query->whereHas('staff.roles', function ($query) use ($request) {
                $query->where('id', $request->role_id);
            });
        }
        if ($request->per_page || $request->page) {
            return $query->orderBy('id', 'desc')->paginate(config('common.list_count'));
        } else {
            return $query->orderBy('id', 'desc')->get();
        }
    }
    public function getOkrAssignById(int $okrAssignId)
    {
        $okrAssign = ObjectiveStaff::with([
            'staff.department',
            'staff.roles',
            'objective.objectiveKeys',
            'objective.sop'
        ])->findOrFail($okrAssignId);
        return $okrAssign;
    }

    public function deleteOkrAssignById(int $okrAssignId)
    {
        $okrAssign = ObjectiveAssign::findOrFail($okrAssignId);
        $okrAssign->objectiveStaff()->delete();
        $okrAssign->delete();
        return $okrAssign;
    }

    public function getAssignDutiesByObjectiveKeys(Request $request, $assignDutyId = null)
    {
        if ($assignDutyId) {
            $objKeyDuty = ObjectiveKeyDuty::with([
                'objectivekeyStaff.objectiveKey.objective',
                'objectivekeyStaff.staff.department',
                'objectivekeyStaff.staff.roles',
            ])->findOrFail($assignDutyId);
            return new AssignResource($objKeyDuty);
        } else {
            // $objKeyDuties =  ObjectiveKeyDuty::with([
            //     'objectivekeyStaff.objectiveKey.objective',
            //     'objectivekeyStaff.staff.department',
            //     'objectivekeyStaff.staff.roles'
            // ])->get();
            // return AssignResource::collection($objKeyDuties);
            return ObjectiveKeyDuty::paginate();
        }
    }

    public function deleteAssignDutiesById($assignDutyId)
    {
        // $objKeyDuty = ObjectiveKeyDuty::findOrFail($assignDutyId);
        // $objKeyDuty->objectivekeyStaff()->delete();
        // $objKeyDuty->delete();
        // return $objKeyDuty;
    }

    public function deleteAssignObjKeyStaffById(int $objKeyStaffId)
    {
        // $objKeyStaff = ObjectivekeyStaff::findOrFail($objKeyStaffId);
        // $objKeyStaff->delete();
        // return $objKeyStaff;
    }

    //mobile
    public function objectiveLists(Request $request)
    {
        $currentDate = now()->toDateString();
        $staffId = UserData()->id;

        $objectiveStaffFilter = function ($query) use ($staffId, $currentDate) {
            $query->where('staff_id', $staffId)
                ->whereDate('start_date', $currentDate);
        };
        $objectives = Objective::with([
            'objectiveStaff' => $objectiveStaffFilter,
            'objectiveKeys'
        ])->whereHas('objectiveStaff', $objectiveStaffFilter)
            ->get();
        return $objectives;
        // return ObjectiveResource::collection($objectives);
    }

    //objkeylistwithstaff assigns 
    public function getdailyObjectives(Request $request, $objId)
    {
        $currentDate = now()->toDateString();

        $objectives = ObjectiveStaff::with([
            'objective.objectiveKeys',
            'objStaffImg'
        ])
            ->where('staff_id', UserData()->id)
            ->whereDate('start_date', $currentDate)
            ->whereHas('objective', function ($query) use ($objId) {
                $query->where('id', $objId);
            })
            ->get();
        return DailyObjKeyStaffResource::collection($objectives);
    }

    public function getdailyObjectivesByStaffId(Request $request, $staffId)
    {
        $currentDate = now()->toDateString();
        $objectiveAssigns = ObjectiveAssign::with([
            'objective.objectiveKeys',
            // 'objectiveStaff' =>function($query) use ($currentDate){
            //     $query->whereDate('start_date', $currentDate);
            //     // ->orderBy('repetition_count', 'asc');
            // }
        ])->where('staff_id', $staffId)
            ->whereHas('objectiveStaff', function ($query) use ($currentDate) {
                $query->whereDate('start_date', $currentDate);
            })->get();

        foreach ($objectiveAssigns as $objectiveAssign) {
            $objType = $objectiveAssign->objective->type;
            if ($objType == 'daily') {
                $objectiveStaff = ObjectiveStaff::where('objective_assign_id', $objectiveAssign->id)
                    ->orderBy('repetition_count', 'asc')
                    ->where('status', 'in_progress')
                    ->whereDate('start_date', $currentDate)
                    ->first();
                if (!$objectiveStaff) {
                    $objectiveStaff = ObjectiveStaff::where('objective_assign_id', $objectiveAssign->id)
                        ->where('status', 'assigned')
                        ->whereDate('start_date', $currentDate)
                        ->orderBy('repetition_count', 'asc')
                        ->first();
                }

                if (!$objectiveStaff && $objectiveAssign->objective->repetition > 1) {
                    $lastCompletedRepetition = ObjectiveStaff::where('objective_assign_id', $objectiveAssign->id)
                        ->whereDate('start_date', $currentDate)
                        ->where('status', 'completed')
                        ->max('repetition_count');
                    if ($lastCompletedRepetition && $lastCompletedRepetition < $objectiveAssign->objective->repetition) {
                        $nextRepetition = $lastCompletedRepetition + 1;
                        $objectiveStaff = ObjectiveStaff::where('objective_assign_id', $objectiveAssign->id)
                            ->whereDate('start_date', $currentDate)
                            ->where('repetition_count', $nextRepetition)
                            ->first();
                    }
                }

                if ($objectiveStaff) {
                    $objectiveStaffCollection = collect([$objectiveStaff]);
                    $objectiveAssign->setRelation('objectiveStaff', $objectiveStaffCollection);

                    $totalRepetition = $objectiveAssign->objective->repetition;
                    $completedRepetitions = ObjectiveStaff::where('objective_assign_id', $objectiveAssign->id)
                        ->whereDate('start_date', $currentDate)
                        ->whereIn('status', ['completed', 'approved'])
                        ->count();
                    $objectiveAssign->objective->remaining_repetitions = $totalRepetition - $completedRepetitions;
                    foreach ($objectiveAssign->objective->objectiveKeys as $objectiveKey) {
                        $completedObjectiveKey = CompletedObjectiveKey::where('objective_key_id', $objectiveKey->id)
                            ->where('objective_staff_id', $objectiveStaff->id)
                            ->first();
                        $objectiveKey->is_done = $completedObjectiveKey ? 1 : 0;
                    }
                } else {
                    $objectiveAssign->setRelation('objectiveStaff', collect([]));
                }
            } else {
                $objectiveStaff = ObjectiveStaff::where('objective_assign_id', $objectiveAssign->id)
                    ->whereDate('start_date', $currentDate)
                    ->get();
                $objectiveAssign->setRelation('objectiveStaff', $objectiveStaff);

                foreach ($objectiveStaff as $objStaff) {
                    foreach ($objectiveAssign->objective->objectiveKeys as $objectiveKey) {
                        $completedObjectiveKey = CompletedObjectiveKey::where('objective_key_id', $objectiveKey->id)
                            ->where('objective_staff_id', $objStaff->id)
                            ->first();
                        $objectiveKey->is_done = $completedObjectiveKey ? 1 : 0;
                    }
                }
            }
        }
        return $objectiveAssigns;
        // return dailyObjectiveByStaffId::collection($objectiveKeyStaff);
    }

    public function getObjKeyStaffImage($objStaffId)
    {
        return ObjectiveStaffImage::with('objectiveStaff')->where('objective_staff_id', $objStaffId)->get();
    }
    public function storeImages($validatedData, $objStaffId)
    {
        if (isset($validatedData['images'])) {

            $storedImages = [];
            foreach ($validatedData['images'] as $data) {

                $extension = $data->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $image_path = $data->storeAs('okrImages/', $hashedName, 'public');
                $image_url = Storage::url($image_path);

                $storedImages[] = ObjectiveStaffImage::create([
                    'objective_staff_id' => $objStaffId,
                    'image_path' => $image_path,
                    'image_url' => $image_url,
                ]);
            }
            return $storedImages;
        } else {
            throw new \Exception('Invalid Image');
        }
    }

    public function deleteObjKeystaffImage($imgId)
    {
        $data = ObjectiveStaffImage::findOrFail($imgId);
        if ($data->image_path && Storage::exists($data->image_path)) {
            Storage::delete($data->image_path);
        }
        $data->delete();
        return $data;
    }

    public function updateImages($validatedData, $objKeyStaffId)
    {
        $data = ObjectiveStaffImage::findOrFail($objKeyStaffId);

        $updateImages = [];

        if (isset($validatedData['images'])) {

            if ($data->image_path && Storage::exists($data->image_path)) {
                Storage::delete($data->image_path);
            }
            foreach ($validatedData['images'] as $objImage) {

                $extension = $objImage->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $image_path = $objImage->storeAs('okrImages/', $hashedName, 'public');
                $image_url = Storage::url($image_path);

                $updateImages[] = $data->update([
                    'objective_staff_id' => $data->id,
                    'image_path' => $image_path,
                    'image_url' => $image_url,
                ]);
            }
        } else {
            throw new \Exception('Invalid image.');
        }
        return $data;
    }

    public function updateDailyObjective($data, $objStaffId)
    {
        $objStaff =  ObjectiveStaff::findOrFail($objStaffId);
        $updateData = [];
        $userId = UserData()->id;
        if (!checkRoles(['Supervisor', 'Manager']) && in_array($data['status'], ['approved', 'cancelled'])) {
            ResponseMessage('Permission is not allowed', 403);
            return;
        }

        if (checkRoles(['Supervisor'])) {
            $updateData = $this->getSupervisorUpdateData($data, $userId);
        } elseif (checkRoles(['Manager'])) {
            $updateData = $this->getManagerUpdateData($data, $userId);
        } else {
            $updateData = $this->getStaffUpdateData($data, $userId, $objStaffId);
        }

        $objStaff->update($updateData);

        return $objStaff;
    }

    private function getSupervisorUpdateData($data, $userId)
    {
        $updateData = ['status' => $data['status']];

        if ($data['status'] === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['okr_point'] = $data['okr_point'];
            $updateData['approved_by'] = $userId;
            if (isset($data['remark'])) {
                $updateData['remark'] = $data['remark'];
            }
        }

        if ($data['status'] === 'cancelled') {
            $updateData['cancelled_at'] = now();
            // $updateData['okr_point'] = $data['okr_point'];
            $updateData['cancelled_by'] = $userId;
        }

        return $updateData;
    }

    private function getManagerUpdateData($data, $userId)
    {
        $updateData = ['status' => $data['status']];

        if ($data['status'] === 'approved') {
            $updateData['manager_checked_at'] = now();
            $updateData['okr_point'] = $data['okr_point'];
            $updateData['manager_checked_by'] = $userId;
            if (isset($data['remark'])) {
                $updateData['remark'] = $data['remark'];
            }
        }

        if ($data['status'] === 'cancelled') {
            $updateData['cancelled_at'] = now();
            $updateData['cancelled_by'] = $userId;
        }

        return $updateData;
    }

    private function getStaffUpdateData($data, $userId, $objStaffId)
    {
        $updateData = ['status' => $data['status']];
        if ($data['status'] == 'in_progress') {
            $updateData['in_progressed_at'] = now();
            $updateData['in_progressed_by'] = $userId;

            if (isset($data['complete_okr_keys'])) {
                $completeOkrKeys = json_decode($data['complete_okr_keys'], true);
                if (!is_array($completeOkrKeys)) {
                    return ResponseMessage('Invalid JSON format for OKR assigns.', 400);
                }
                foreach ($completeOkrKeys as $completeOkrKey) {
                    CompletedObjectiveKey::updateOrCreate(
                        [
                            'objective_key_id' => $completeOkrKey['objective_key_id'],
                            'objective_staff_id' => $objStaffId
                        ],
                        [
                            'objective_key_id' => $completeOkrKey['objective_key_id'],
                            'objective_staff_id' => $objStaffId,
                        ]
                    );
                }
            }
        }

        if ($data['status'] === 'completed') {
            $updateData['completed_at'] = now();
            $updateData['completed_by'] = $userId;
            if (isset($data['remark'])) {
                $updateData['remark'] = $data['remark'];
            }
        }

        return $updateData;
    }

    public function storeCompletedObjKeys($data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['complete_okr_keys'])) {
                $completeOkrKeys = json_decode($data['complete_okr_keys'], true);
                if (!is_array($completeOkrKeys)) {
                    return ResponseMessage('Invalid JSON format for OKR assigns.', 400);
                }
                foreach ($completeOkrKeys as $completeOkrKey) {
                    CompletedObjectiveKey::updateOrCreate(
                        [
                            'id' => $completeOkrKey['id'] ?? null
                        ],
                        [
                            'objective_key_id' => $completeOkrKey['objective_key_id'],
                            'objective_staff_id' => $completeOkrKey['objective_staff_id'],
                        ]
                    );
                }
            }
            DB::commit();
            return $completeOkrKeys;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getCompletedObjKeysByStaffId($staffId)
    {
        $objectives = Objective::with([
            'objectiveKeys',
            'objectiveAssigns' => function ($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            },
            'objectiveAssigns.objectiveStaff' => function ($query) {
                $query->whereIn('status', ['completed', 'approved']);
            }
        ])->whereHas('objectiveAssigns', function ($q) use ($staffId) {
            $q->where('staff_id', $staffId);
            $q->whereHas('objectiveStaff', function ($query) {
                $query->whereIn('status', ['completed', 'approved']);
            });
        })->get();
        return $objectives;
    }

    //ktv
    public function getKtvRoom(Request $request)
    {
        return Entity::where('entity_type', 'room')->get();
    }

    public function getKtvObjective(Request $request, $departmentId)
    {

        $ktvObjectives = ObjectiveKey::with([
            'role.department'
        ])->whereHas('role.department', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->get();

        // return $ktvObjectives;
        return KtvObjectiveRsource::collection($ktvObjectives);
    }

    public function storeKtvObjectiveTree(Request $request)
    {

        DB::beginTransaction();
        try {
            $validatedData = $request->all();

            $validatedData['created_by'] = UserData()->id;
            $ktvProductTree = KtvProductTree::create($validatedData);

            $ktvObjs = json_decode($validatedData['objective_keys']);

            foreach ($ktvObjs as $ktvObjective) {
                KtvObjective::create([
                    'ktv_product_tree_id' => $ktvProductTree->id,
                    'objective_key_id' => $ktvObjective
                ]);
            }
            $ktvItems = json_decode($validatedData['items']);
            foreach ($ktvItems as $ktvItem) {
                KtvItem::create([
                    'ktv_product_tree_id' => $ktvProductTree->id,
                    'item_id' => $ktvItem->item_id,
                    'quantity' => $ktvItem->quantity
                ]);
            }
            DB::commit();
            return $ktvProductTree;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getKtvObjectiveTree(Request $request)
    {
        $data = KtvProductTree::with([
            'entity',
            'KtvObjectives.objectiveKey.role',
            'KtvItems.item'
        ])->paginate();
        return $data;
    }
    public function getKtvObjTreeById(Request $request, $id)
    {
        $data = KtvProductTree::with([
            'entity',
            'KtvObjectives.objectiveKey.role',
            'KtvItems.item'
        ])->findOrFail($id);
        return $data;
    }

    public function updateKtvObjTree(Request $request, $ktvObjTreeId)
    {
        $validatedData = $request->all();

        DB::beginTransaction();
        try {

            $ktvProductTree = KtvProductTree::findOrFail($ktvObjTreeId);
            $validatedData['created_by'] = UserData()->id;

            $ktvProductTree->update($validatedData);

            $ktvProductTree->ktvObjectives()->delete();
            $ktvProductTree->ktvItems()->delete();
            $ktvObjs = json_decode($validatedData['objective_keys']);
            foreach ($ktvObjs as $ktvObjectiveKey) {
                $ktvProductTree->KtvObjectives()->create([
                    'ktv_product_tree_id' => $ktvProductTree->id,
                    'objective_key_id' => $ktvObjectiveKey
                ]);
            }
            $ktvItems = json_decode($validatedData['items'], true);
            foreach ($ktvItems as $ktvItem) {
                $ktvProductTree->KtvItems()->create([
                    'ktv_product_tree_id' => $ktvProductTree->id,
                    'item_id' => $ktvItem['item_id'],
                    'quantity' => $ktvItem['quantity']
                ]);
            }
            DB::commit();
            return $ktvProductTree;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
