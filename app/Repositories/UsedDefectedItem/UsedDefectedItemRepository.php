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

            $totalCount = UsedDefectedItem::with('item', 'uom')->whereBetween('created_at', [$startTime, $endTime])->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $used_defected_items = UsedDefectedItem::with('item', 'uom')->whereBetween('date', [$startTime, $endTime])
                ->skip($skip)
                ->take($perPage)
                ->get();
            $paginationData = MakePaginationData($request, $totalCount, 'used_defected_items');
            $paginationData['used_defected_items'] = $used_defected_items;

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
            $itemInventories = InventoryLedgerItem::where('item_id', $data['item_id'])->get();
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
            $latestItemPrice = $item->item_prices()->orderBy('created_at', 'desc')->first();
            if ($latestItemPrice->uom_id == $data['uom_id']) {
                $value = $data['quantity'] * $item->uomConversion->conversion;
            } else if ($data['base_uom_id'] == $data['uom_id']) {
                $uomRate = UomConversion::where('base_unit_id', $data['uom_id'])->where('conversion_unit_id', $latestItemPrice->uom_id)->first();
                // dd($uomRate->conversion);
                $value = $data['quantity'] / $uomRate->conversion;
            } else {
                ResponseMessage('Given Uom cannot be caculate, please selecte proper Uom', 402);
            }
            if ($value > $stockInInventory) {
                ResponseMessage("Stock is not enough", 402);
            }

            $uomConversion = UomConversion::where('base_unit_id', $data['base_uom_id'])->where('conversion_unit_id', $data['uom_id'])->first();
            // $data['uom_conversion_id'] = $uomConversion->id;
            $data['created_by'] = UserData()->id;
            $data['date'] = CurrentTime();
            $usedDefectedItem = UsedDefectedItem::create($data);

            $inventoryId = UserData()->department->inventory->inventory_id;

            $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($usedDefectedItem, 'used_defect_item', 'out');

            $inventoryLedger->inventory_ledger_items()->create([
                'item_id' => $item->id,
                'quantity' => $value,
                'inventory_ledger_id' => $inventoryLedger->id,
            ]);

            $inventoryLedgerItem = InventoryLedgerItem::where('item_id', $data['item_id'])->first();
            DB::commit();
            return $usedDefectedItem;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
