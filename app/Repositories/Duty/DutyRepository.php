<?php

namespace App\repositories\Duty;

use App\Models\Duty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DutyRepository implements DutyRepositoryInterface
{
    public function createDuty(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $dutyArray = json_decode($data['dutyArray'], true);
            foreach ($dutyArray as $dutyData) {
                $dutyData['created_by'] = UserData()->id;
                $duty = Duty::create($dutyData);
                if (isset($dutyData['taskIds'])) {
                    foreach ($dutyData['taskIds'] as $taskId) {
                        $duty->tasks()->attach($taskId);
                    }
                }
            }
            DB::commit();
            ResponseData($duty);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

}
