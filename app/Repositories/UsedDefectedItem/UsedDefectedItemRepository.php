<?php

namespace App\Repositories\UsedDefectedItem;

use App\Models\InventoryLedgerItem;
use App\Models\Item;
use App\Models\UomConversion;
use App\Models\UsedDefectedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsedDefectedItemRepository implements UsedDefectedItemRepositoryInterface
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

            $totalCount = UsedDefectedItem::with('item','uom')->whereBetween('created_at', [$startTime, $endTime])->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $used_defected_items = UsedDefectedItem::with('item','uom')->whereBetween('date', [$startTime, $endTime])
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
                $usedDefectedItem = UsedDefectedItem::with('item','uom')->whereBetween('date', [$startTime, $endTime])->get();
            }else{
                $usedDefectedItem = UsedDefectedItem::with('item','uom')->get();
            }

            return $usedDefectedItem;
        }
    }
    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $item = Item::find($data['item_id']);
            $uomConversion = UomConversion::where('base_unit_id',$data['base_uom_id'],'conversion_unit_id',$data['uom_id'])->first()->id;
            dd($uomConversion);
            dd('stop');
            $data['uom_conversion_id'] = $uomConversion->id;
            $data['created_by'] = UserData()->id;
            $data['date'] = CurrentTime();
            $usedDefectedItem = UsedDefectedItem::create($data);

            $inventoryLedgerItem = InventoryLedgerItem::where('item_id',$data['item_id'])->first();

            DB::commit();
            return $usedDefectedItem;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
