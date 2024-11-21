<?php

namespace App\Repositories\Objective;

use Exception;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Objective;
use App\Models\ObjectiveKey;
use Illuminate\Http\Request;
use App\Models\ObjectivekeyImage;
use App\Models\ObjectivekeyStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class  ObjectiveRepository implements ObjectiveInterface
{

    public function getObjectives(Request $request)
    {
        return Objective::with(['role', 'objectiveKeys'])->paginate(config('common.list_count'));
    }
    public function getRolesByDepartmentId(Request $request, $departmentId)
    {

        return Role::with('department')->where('department_id', $departmentId)->get();
    }



    public function getObjectiveById(Request $request, $objId)
    {
        return Objective::with(['role', 'objectiveKeys'])->where('id', $objId)->get();
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

            $data['assigned_days'] = is_string($data['assigned_days'])
                ? json_decode($data['assigned_days'], true)
                : $data['assigned_days'];
            $data['assigned_days'] = implode(',', $data['assigned_days'] ?? []);
            $data['created_by'] = UserData()->id;

            if ($objId) {
                $objective = Objective::findOrFail($objId);
                $objective->update($data);
            } else {
                $objective = Objective::create($data);
            }

            $this->syncObjectiveKeys($objective, $data['objective_key']);
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
            $objectiveKeys = json_decode($objectiveKeys);

            $objective->objectiveKeys()->delete();

            foreach ($objectiveKeys as $key) {
                ObjectiveKey::create([
                    'objective_id' => $objective->id,
                    'name' => $key->name,
                    'okr_point' => $key->okr_point
                ]);
            }
        }
    }

    public function objectiveLists(Request $request)
    {
        $currentDay = now()->format('l');
        return  Objective::with(['objectiveKeys.objKeyStaff', 'objectiveKeys.objImages'])
            ->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay])
            ->paginate();
    }

    //objkeylistwithstaff assigns 
    public function getdailyObjectives(Request $request)
    {
        $currentDay = now()->format('l');

        $objectives = ObjectivekeyStaff::with([
            'objectiveKey.objective',
            'objectiveKey.objImages'
        ])
            ->where('staff_id', UserData()->id)
            ->whereHas('objectiveKey.objective', function ($query) use ($currentDay) {
                $query->whereRaw("FIND_IN_SET(?, assigned_days)", [$currentDay]);
            })
            ->paginate();
        return $objectives;
    }

    public function storeImages($validatedData)
    {
        if (isset($validatedData['image']) && is_string($validatedData['image'])) {
            $datas = json_decode($validatedData['image'], true);
            $storedImages = [];
            foreach ($datas as $data) {

                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('okrImages/', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);

                $storedImages[] = ObjectivekeyImage::create([
                    'objective_key_id' => $validatedData['objective_key_id'],
                    'image_path' => $data['image_path'],
                    'image_url' =>  $data['image_url'],
                ]);
            }
            return $storedImages;
        } else {
            throw new \Exception('Invalid Image');
        }
    }

    public function updateImages($validatedData, $objKeyImgId)
    {
        $data = ObjectivekeyImage::findOrFail($objKeyImgId);

        $updateImages = [];

        if (isset($validatedData['image']) && $validatedData['image']->isValid()) {

            if ($data->image_path && Storage::exists($data->image_path)) {
                Storage::delete($data->image_path);
            }

            $objImages = json_decode($validatedData['image'], true);
            foreach ($objImages as $objImage) {
                $imageData = $objImage['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $objImage['imagePath'] = $imageData->storeAs('okrImages/', $hashedName, 'public');
                $objImage['imageUrl'] = Storage::url($objImage['imagePath']);

                $updateImages[] = $data->update([
                    'objective_key_id' => $data->id,
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
            ResponseMessage('Permission is not allowed', 403);
        }
        $objKeyStaff->update($updateData);

        return $updateData;
    }
}
