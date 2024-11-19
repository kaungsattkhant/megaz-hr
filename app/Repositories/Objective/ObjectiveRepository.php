<?php

namespace App\Repositories\Objective;

use Exception;
use App\Models\Objective;
use App\Models\ObjectiveKey;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class  ObjectiveRepository implements ObjectiveInterface
{
    public function getObjectives(Request $request)
    {
        return Objective::with(['role', 'objectiveKeys'])->get();
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
}
