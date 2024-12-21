<?php

namespace App\Repositories\Objective;

use App\Http\Resources\DailyObjKeyStaffResource;
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
use App\Models\ObjectivekeyStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ObjectiveKeyStaffImage;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\KtvObjectiveRsource;
use App\Http\Resources\KtvProductTreeEditResource;

class  ObjectiveRepository implements ObjectiveInterface
{

    public function getObjectives(Request $request)
    {
        $search = $request->input('search');

        $roleId = $request->input('roleId');

        return Objective::with([
            'objectiveKeys.role.department',
        ])
            ->objectiveFilter($search, $roleId)
            ->paginate();
    }
    public function getRolesByDepartmentId(Request $request, $departmentId)
    {

        return Role::with('department')->where('department_id', $departmentId)->get();
    }

    public function getObjectiveById(Request $request, $objId)
    {
        return Objective::with(['objectiveKeys.role.department'])->where('id', $objId)->get();
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
        return $this->saveObjectiveData($validatedData);
    }

    public function update(array $validatedData, int $objId)
    {
        return $this->saveObjectiveData($validatedData, $objId);
    }

    private function saveObjectiveData(array $data, int $objId = null)
    {
        DB::beginTransaction();
        try {

            $data['created_by'] = UserData()->id;

            if ($objId) {
                $objective = Objective::findOrFail($objId);
                $objective->update($data);
            } else {
                $objective = Objective::create($data);
            }

            if (isset($data['objective_key'])) {
                $this->syncObjectiveKeys($objective, $data['objective_key']);
            }

            DB::commit();
            return $objective;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function syncObjectiveKeys(Objective $objective, string $objectiveKeys)
    {
        if (!empty($objectiveKeys)) {
            $objectiveKeys = json_decode($objectiveKeys, true);

            $objective->objectiveKeys()->each(function ($objectiveKey) {
                $objectiveKey->objKeyStaff()->delete();
            });

            $objective->objectiveKeys()->delete();

            $currentDay = date('l');

            foreach ($objectiveKeys as $key) {
                $assignedDays = isset($key['assigned_days']) && is_array($key['assigned_days'])
                    ? $key['assigned_days']
                    : [];
                $assignedDaysData = implode(',', $assignedDays); // Convert array to string
                $objKey = ObjectiveKey::create([
                    'objective_id' => $objective->id,
                    'role_id' => $key['role_id'],
                    'name' => $key['name'],
                    'okr_point' => $key['okr_point'],
                    'assigned_days' => $assignedDaysData,
                    'duration' => $key['duration'],
                ]);

                $staffLists = Staff::staffByRole($key['role_id']);

                if (in_array($currentDay, $assignedDays)) {
                    foreach ($staffLists as $staff) {
                        ObjectivekeyStaff::create([
                            'staff_id' => $staff->id,
                            'objective_key_id' => $objKey->id,
                            'status' =>  'not_started',
                            'okr_point' =>  $objKey->okr_point,
                        ]);
                    }
                }
            }
        }
    }

    //mobile
    public function objectiveLists(Request $request)
    {
        $currentDay = now()->format('l');
        $staffId = UserData()->id;

        $data =  Objective::with([
            'objectiveKeys' => function ($query) use ($currentDay, $staffId) {
                $query->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay]);
            },
            'objectiveKeys.objKeyStaff' => function ($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            },
            'objectiveKeys.objKeyStaff.objKeyStaffImg'
        ])
            ->whereHas('objectiveKeys', function ($query) use ($currentDay, $staffId) {
                $query->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay])
                    ->whereHas('objKeyStaff', function ($query) use ($staffId) {
                        $query->where('staff_id', $staffId);
                    });
            })
            ->paginate();
        return $data;
    }



    //objkeylistwithstaff assigns 
    public function getdailyObjectives(Request $request, $objId)
    {
        $currentDay = now()->format('l');

        $objectives = ObjectivekeyStaff::with([
            'objectiveKey' => function ($query) use ($currentDay) {
                $query->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay]);
            },
            'objectiveKey.objective',
        ])
            ->where('staff_id', UserData()->id)
            ->whereHas('objectiveKey.objective', function ($query) use ($currentDay, $objId) {
                $query->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay])
                    ->where('id', $objId);
            })
            ->get();

        return DailyObjKeyStaffResource::collection($objectives);
    }

    public function getdailyObjectivesById(Request $request, $objId)
    {
        $currentDay = now()->format('l');
        $objectives = ObjectiveKey::with([
            'objKeyStaff' => function ($query) {
                $query->where('staff_id', UserData()->id);
            }
        ])
            ->where('objective_id', $objId)
            ->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay])
            ->get();

        return  $objectives;
    }

    public function getObjKeyStaffImage($objKeystaffId)
    {

        return ObjectiveKeyStaffImage::with('objective_keyStaff')->where('objectivekey_staff_id', $objKeystaffId)->get();
    }
    public function storeImages($validatedData, $objKeystaffId)
    {
        if (isset($validatedData['images'])) {

            $storedImages = [];
            foreach ($validatedData['images'] as $data) {

                $extension = $data->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $image_path = $data->storeAs('okrImages/', $hashedName, 'public');
                $image_url = Storage::url($image_path);

                $storedImages[] = ObjectiveKeyStaffImage::create([
                    'objectivekey_staff_id' => $objKeystaffId,
                    'image_path' => $image_path,
                    'image_url' =>  $image_url,
                ]);
            }
            return $storedImages;
        } else {
            throw new \Exception('Invalid Image');
        }
    }

    public function deleteObjKeystaffImage($imgId)
    {
        $data = ObjectiveKeyStaffImage::findOrFail($imgId);
        if ($data->image_path && Storage::exists($data->image_path)) {
            Storage::delete($data->image_path);
        }
        $data->delete();
        return $data;
    }

    public function updateImages($validatedData, $objKeyStaffId)
    {
        $data = ObjectiveKeyStaffImage::findOrFail($objKeyStaffId);

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
                    'objectivekey_staff_id' => $data->id,
                    'image_path' => $image_path,
                    'image_url' =>  $image_url,
                ]);
            }
        } else {
            throw new \Exception('Invalid image.');
        }
        return $data;
    }


    public function updateDailyObjective($data, $objKeyStaffId)
    {
        $objKeyStaff = ObjectivekeyStaff::findOrFail($objKeyStaffId);
        $updateData = [];
        $userId = UserData()->id;

        if (!checkRoles(['Supervisor']) && in_array($data['status'], ['approved', 'cancelled'])) {
            ResponseMessage('Permission is not allowed', 403);
            return;
        }

        if (!checkRoles(['Manager']) && in_array($data['status'], ['approved', 'cancelled'])) {
            ResponseMessage('Permission is not allowed', 403);
            return;
        }

        if (checkRoles(['Supervisor'])) {
            $updateData = $this->getSupervisorUpdateData($data, $userId);
        } elseif (checkRoles(['Manager'])) {
            $updateData = $this->getManagerUpdateData($data, $userId);
        } else {
            $updateData = $this->getStaffUpdateData($data, $userId);
        }

        $objKeyStaff->update($updateData);

        return $updateData;
    }

    private function getSupervisorUpdateData($data, $userId)
    {
        $updateData = ['status' => $data['status']];

        if ($data['status'] === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['okr_point'] = $data['okr_point'];
            $updateData['approved_by'] = $userId;
        }

        if ($data['status'] === 'cancelled') {
            $updateData['cancelled_at'] = now();
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
        }

        if ($data['status'] === 'cancelled') {
            $updateData['cancelled_at'] = now();
            $updateData['cancelled_by'] = $userId;
        }

        return $updateData;
    }

    private function getStaffUpdateData($data, $userId)
    {
        $updateData = ['status' => $data['status']];

        if ($data['status'] == 'in_progress') {
            $updateData['in_progressed_at'] = now();
            $updateData['in_progressed_by'] = $userId;
        }

        if ($data['status'] === 'completed') {
            $updateData['completed_at'] = now();
            $updateData['completed_by'] = $userId;
        }

        return $updateData;
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
            return   $ktvProductTree;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function  getKtvObjectiveTree(Request $request)
    {
        $data =  KtvProductTree::with([
            'entity',
            'KtvObjectives.objectiveKey.role',
            'KtvItems.item'
        ])->paginate();
        return $data;
    }
    public function getKtvObjTreeById(Request $request, $id)
    {
        $data =  KtvProductTree::with([
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
            return   $ktvProductTree;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
