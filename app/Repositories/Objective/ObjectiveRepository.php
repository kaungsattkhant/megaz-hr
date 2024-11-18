<?php

namespace App\Repositories\Objective;

use Exception;
use App\Models\Objective;
use App\Models\ObjectiveKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class  ObjectiveRepository implements ObjectiveInterface
{

    public function store($validatedData)
    {

        DB::beginTransaction();
        try {

            $validatedData['assigned_days'] = is_string($validatedData['assigned_days'])
                ? json_decode($validatedData['assigned_days'], true)
                : $validatedData['assigned_days'];
            $validatedData['assigned_days'] = implode(',', $validatedData['assigned_days'] ?? []);

            $objective = Objective::create(
                $validatedData
            );
           
            $objKeys = json_decode($validatedData['objective_key'],true);

            foreach ($objKeys as  $objKey) {
                ObjectiveKey::create([
                    'objective_id' => $objective->id,
                    'name' => $objKey['name'],
                    'okr_point' => $objKey['okr_point']

                ]);
            }

            DB::commit();
            return response()->json([
                'message' => 'objective stored successfully!',
                'data' =>  $objective
            ], 201);
        } catch (Exception $e) {
            ResponseMessage($e->getMessage(), 500);
            throw $e;
        }
    }
}
