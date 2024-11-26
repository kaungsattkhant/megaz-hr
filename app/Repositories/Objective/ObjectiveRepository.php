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
        $days = $request->input('days');
        $name = $request->input('name');
        $role = $request->input('role');
        $department = $request->input('department');

        return Objective::with([
            'role.department',
            'objectiveKeys.objKeyStaff.objKeyStaffImg',
        ])->objectiveFilter($search, $name, $days, $role, $department)
            ->paginate();
    }
    public function getRolesByDepartmentId(Request $request, $departmentId)
    {

        return Role::with('department')->where('department_id', $departmentId)->get();
    }

    public function getObjectiveById(Request $request, $objId)
    {
        return Objective::with(['role.department', 'objectiveKeys.objKeyStaff.objKeyStaffImg'])->where('id', $objId)->get();
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

            $objective->objectiveKeys()->delete();
            $roleId =  $objective->role_id;

            $staffLists = Staff::staffByRole($roleId);

            $currentDay = date('l');

            foreach ($objectiveKeys as $key) {
                $assignedDays = isset($key['assigned_days']) && is_array($key['assigned_days'])
                    ? $key['assigned_days']
                    : [];
                $assignedDaysData = implode(',', $assignedDays); // Convert array to string
                $objKey = ObjectiveKey::create([
                    'objective_id' => $objective->id,
                    'name' => $key['name'],
                    'okr_point' => $key['okr_point'],
                    'assigned_days' => $assignedDaysData,
                    'duration' => $key['duration'],
                ]);

                if (in_array($currentDay, $assignedDays)) {
                    foreach ($staffLists as $staff) {
                        ObjectivekeyStaff::create([
                            'staff_id' => $staff->id,
                            'objective_key_id' => $objKey->id,
                            'status' =>  'not_started',
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
            'role',
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
    public function getdailyObjectives(Request $request)
    {
        $currentDay = now()->format('l');

        $objectives = ObjectivekeyStaff::with([
            'objectiveKey' => function ($query) use ($currentDay) {
                $query->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay]);
            },
            'objectiveKey.objective',
            'objKeyStaffImg'
        ])
            ->where('staff_id', UserData()->id)
            ->whereHas('objectiveKey', function ($query) use ($currentDay) {
                $query->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay]);
            })
            ->paginate();
        return $objectives;
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

    public function updateImages($validatedData, $objKeyImgId)
    {
        $data = ObjectiveKeyStaffImage::findOrFail($objKeyImgId);

        $updateImages = [];

        if (isset($validatedData['images'])) {

            if ($data->image_path && Storage::exists($data->image_path)) {
                Storage::delete($data->image_path);
            }

            $objImages = json_decode($validatedData['images'], true);
            foreach ($objImages as $objImage) {
                $imageData = $objImage['images'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $objImage['imagePath'] = $imageData->storeAs('okrImages/', $hashedName, 'public');
                $objImage['imageUrl'] = Storage::url($objImage['imagePath']);

                $updateImages[] = $data->update([
                    'objectivekey_staff_id' => $data->id,
                    'image_path' => $objImage['imagePath'],
                    'image_url' => $objImage['imageUrl'],
                ]);
            }
        } else {
            throw new \Exception('Invalid image.');
        }
        return $updateImages;
    }


    public function updateDailyObjective($data, $objKeyStaffId)
    {
        $objKeyStaff = ObjectivekeyStaff::findOrFail($objKeyStaffId);
        $updateData = [];
        $userId = UserData()->id;

        if (!checkRoles(['Supervisor']) && $data['status'] === 'approved' || !checkRoles(['Supervisor']) && $data['status'] === 'cancelled') {
            ResponseMessage('Permission is not allowed', 403);
            return;
        }

        if (checkRoles(['Supervisor'])) {
            $updateData = [
                'status' => $data['status'],
            ];
            if ($data['status'] === 'approved') {
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = $userId;
            }

            if ($data['status'] === 'cancelled') {
                $updateData['cancelled_at'] = now();
                $updateData['cancelled_by'] = $userId;
            }
        } else {

            if ($data['status'] == 'in_progress') {
                $updateData['status'] = $data['status'];
                $updateData['in_progressed_at'] = now();
                $updateData['in_progressed_by'] = $userId;
            }

            if ($data['status'] === 'completed') {

                $updateData['status'] = $data['status'];
                $updateData['completed_at'] = now();
                $updateData['completed_by'] = $userId;
            }
        }
        $objKeyStaff->update($updateData);

        return $updateData;
    }

    //ktv
    public function getKtvRoom(Request $request)
    {
        return Entity::where('entity_type', 'room')->get();
    }

    public function getKtvObjective(Request $request)
    {

        $ktvObjectives = Objective::with([
            'role',
            'objectiveKeys'
        ])->get();

        return KtvObjectiveRsource::collection($ktvObjectives);
    }

    public function storeKtvObjectiveTree(Request $request)
    {

        DB::beginTransaction();
        try {
            $validatedData = $request->all();

            $validatedData['created_by'] = UserData()->id;
            $ktvProductTree = KtvProductTree::create($validatedData);

            $ktvObjs = json_decode($validatedData['objectives']);

            foreach ($ktvObjs as $ktvObjective) {
                KtvObjective::create([
                    'ktv_product_tree_id' => $ktvProductTree->id,
                    'objective_id' => $ktvObjective
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
            'KtvObjectives.objective.role',
            'KtvObjectives.objective.objectiveKeys',
            'KtvItems.item'
        ])->paginate();
        return $data;
    }
    public function getKtvObjTreeById(Request $request, $id)
    {
        $data =  KtvProductTree::with([
            'entity',
            'KtvObjectives.objective.role',
            'KtvObjectives.objective.objectiveKeys',
            'KtvItems.item'
        ])->findOrFail($id);
        return new KtvProductTreeEditResource($data);
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
            $ktvObjs = json_decode($validatedData['objectives']);
            foreach ($ktvObjs as $ktvObjective) {
                $ktvProductTree->KtvObjectives()->create([
                    'ktv_product_tree_id' => $ktvProductTree->id,
                    'objective_id' => $ktvObjective
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
