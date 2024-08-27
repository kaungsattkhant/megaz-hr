<?php

namespace App\repositories\Duty;

use App\Models\Duty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DutyRepository implements DutyRepositoryInterface
{

    public function listDuties(Request $request)
    {
        $date = $request->query('date');
        $fromDate = $request->query('from_date') ;
        $toDate = $request->query('to_date');
        $query = Duty::with('staff', 'cookingPlace', 'tasks');

        if ($date) {
            $query->whereDate('date', $date);
        } elseif ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        } else {
            $query->whereDate('date', CurrentDate());
        }
        $duties = $query->orderBy('created_at', 'desc')->paginate(config('common.list_count'));
        ResponseData($duties);
    }


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

    public function updateDuty(Request $request,int $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $duty = Duty::findOrFail($id);
            $duty->update($data);
            if (isset($data['taskIds'])) {
                $duty->tasks()->detach();
                $tasks = json_decode($data['taskIds'], true);
                foreach ($tasks as $taskId) {
                    $duty->tasks()->attach($taskId);
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

    public function deleteDuty(int $id)
    {
        DB::beginTransaction();
        try {
            $duty = Duty::findOrFail($id);
            $duty->delete();
            DB::commit();
            ResponseMessage('Duty deleted successfully',200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

}
