<?php

namespace App\Repositories\UsedDefectedItem;

use App\Models\UsedDefectedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsedDefectedItemRepository implements UsedDefectedITemRepositoryInterface
{
    public function listUsedDefectList(Request $request)
    {
        if ($request->per_page || $request->page) {
            if($request->date)
            {
                $date = $request->date;
            }else{
                $date = CurrentDate();
            }
            $startTime = $date . ' 00:00:00';
            $endTime = $date . ' 23:59:59';

            $totalCount = UsedDefectedItem::whereBetween('created_at', [$startTime, $endTime])->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $used_defected_items = UsedDefectedItem::whereBetween('date', [$startTime, $endTime])
                            ->skip($skip)
                            ->take($perPage)
                            ->get();
            $paginationData = MakePaginationData($request, $totalCount, 'used_defected_items');
            $paginationData['used_defected_items'] = $used_defected_items;

            return $paginationData;
        } else {
            if($request->date)
            {
                $startTime = $request->date . ' 00:00:00';
                $endTime = $request->date . ' 23:59:59';
                $usedDefectedItem = UsedDefectedItem::whereBetween('date', [$startTime, $endTime])->get();
            }else{
                $usedDefectedItem = UsedDefectedItem::all();
            }

            return $usedDefectedItem;
        }
    }
    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $data['date'] = CurrentTime();
            $usedDefectedItem = UsedDefectedItem::create($data);
            DB::commit();
            return $usedDefectedItem;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
