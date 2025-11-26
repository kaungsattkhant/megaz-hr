<?php

namespace App\Repositories\UsedDefectedItem;

use App\Http\Action\Inventory\StoreInventory;
use App\Models\InventoryLedgerItem;
use App\Models\Item;
use App\Models\UomConversion;
use App\Models\UsedDefectedItem;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsedDefectedItemRepository implements UsedDefectedItemRepositoryInterface
{
    public function listUsedDefectList(Request $request)
    {
        if ($request->per_page || $request->page) {
            if ($request->date) {
                $date = $request->date;
            } else {
                $date = CurrentDate();
            }
            $startTime = $date . ' 00:00:00';
            $endTime = $date . ' 23:59:59';

            $paginationData = UsedDefectedItem::with('item', 'uom')->whereBetween('created_at', [$startTime, $endTime])->paginate(config('common.list_count'));

            return $paginationData;
        } else {
            if ($request->date) {
                $startTime = $request->date . ' 00:00:00';
                $endTime = $request->date . ' 23:59:59';
                $usedDefectedItem = UsedDefectedItem::with('item', 'uom')->whereBetween('date', [$startTime, $endTime])->get();
            } else {
                $usedDefectedItem = UsedDefectedItem::with('item', 'uom')->get();
            }

            return $usedDefectedItem;
        }
    }


    public function createData(array $data)
    {
        DB::beginTransaction();
        try {

            $item = Item::find($data['item_id']);
            $uomConversion = UomConversion::where('base_unit_id', $data['uom_id'])->where('conversion_unit_id', $item->base_uom_id)->first();
            if (!$uomConversion) {
                ResponseMessage('Uom Conversion not found', 404);
            }
            $data['uom_conversion_id'] = $uomConversion->id;
            $data['created_by'] = UserData()->id;
            // dd(UserData()->department->inventory->inventory_id);
            if(!isset($data['inventory_id']))
            {
                $data['inventory_id'] = UserData()->department->inventory->inventory_id;
            }

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

    public function confirmUsedDefect(int $id)
    {
        DB::beginTransaction();
        try {
            $usedDefectItem = UsedDefectedItem::find($id);
            $usedDefectItem->confirmed_by = UserData()->id;
            $usedDefectItem->is_confirmed = 1;
            $usedDefectItem->confirmed_at = CurrentTime();
            $usedDefectItem->save();

            $item = Item::find($usedDefectItem->item_id);
            $itemInventories = InventoryLedgerItem::where('item_id', $usedDefectItem->item_id)->get();
            $enterInventoryValue = 0;
            $outInventroyValue = 0;

            foreach ($itemInventories as $itemInventory) {
                if ($itemInventory->inventory_ledger->action == 'in') {
                    $enterInventoryValue += $itemInventory->quantity;
                } else if ($itemInventory->inventory_ledger->action == 'out') {
                    $outInventroyValue += $itemInventory->quantity;
                }
            }
            $stockInInventory = $enterInventoryValue - $outInventroyValue;

            $uomConversion = UomConversion::find($usedDefectItem->uom_conversion_id);

            $latestItemPrice = $item->item_prices()->orderBy('created_at', 'desc')->first();

            $value = 0;
            if ($usedDefectItem->uom_id == $uomConversion->conversion_unit_id) {
                $value = $usedDefectItem->quantity;
            } else if ($latestItemPrice->uom_id = $usedDefectItem->uom_id) {
                $value = $usedDefectItem->quantity * $uomConversion->conversion;
            }else{
                ResponseMessage('Please select appropriate uom',402);
            }
            // dd($value,$stockInInventory);
            if ($value > $stockInInventory) {
                ResponseMessage("Stock is not enough", 402);
            }
            $inventoryId = $usedDefectItem->inventory_id;
            $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($usedDefectItem, 'used_defect_item', 'out');

            $inventoryLedger->inventory_ledger_items()->create([
                'item_id' => $usedDefectItem->item_id,
                'quantity' => $value,
                'inventory_ledger_id' => $inventoryLedger->id,
            ]);
            DB::commit();
            ResponseMessage("Used defected Item confirmed", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
