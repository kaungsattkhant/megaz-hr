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

    public function store($validatedData)
    {
       
        DB::beginTransaction();
        try {
         

            $validatedData['assigned_days'] = is_string($validatedData['assigned_days'])
                ? json_decode($validatedData['assigned_days'], true)
                : $validatedData['assigned_days'];
            $validatedData['assigned_days'] = implode(',', $validatedData['assigned_days'] ?? []);
            $validatedData['created_by'] = UserData()->id;
            
           
                $objective = Objective::create(
                    $validatedData
                );

                $objKeys = json_decode($validatedData['objective_key']);

                foreach ($objKeys as  $objKey) {
                    ObjectiveKey::create([
                        'objective_id' => $objective->id,
                        'name' => $objKey->name,
                        'okr_point' => $objKey->okr_point

                    ]);
                }

                $message = 'Objective stored successfully';

            DB::commit();
            return response()->json([
                'message' => $message,
                'data' =>  $objective
            ], 201);
        } catch (Exception $e) {
            ResponseMessage($e->getMessage(), 500);
            throw $e;
        }
    }
}
