<?php

namespace App\Repositories\Inventory;

use App\Models\Staff;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Inventoryable;
use App\Models\InventoryItem;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Actions\Inventory\GetInventoryStockAction;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $inventory_id = $request->inventory_id ?? '';
        $inventory_ids = UserData()->inventories->pluck('id')->toArray();
        // if ($request->per_page || $request->page) {
        //     $inventories = Inventory::where('is_active', 1)
        //         ->whereHas('staff', function ($query) {
        //             $query->where('id', UserData()->id);
        //         })
        //         ->whereIn('id', $inventory_ids)
        //         ->where('name', 'like', '%' . $name . '%')
        //         ->with(['inventoryable'])
        //         ->paginate(config('common.list_count'));
        //     return $inventories;
        // } else {
        //     $inventories = Inventory::where('is_active', 1)
        //         ->whereHas('staff', function ($query) {
        //             $query->where('id', UserData()->id);
        //         })
        //         ->whereIn('id', $inventory_ids)
        //         ->where('name', 'like', '%' . $name . '%')
        //         ->with(['inventoryable'])
        //         ->get();
        //     return $inventories;
        // }

        $query = Inventory::where('is_active', 1)
            // ->whereHas('staff', function ($query) {
            //     $query->where('id', UserData()->id);
            // })
            ->with(['inventoryable']);
        if ($inventory_id) {
            $query->where('id', $inventory_id);
        }
        if ($request->per_page || $request->page) {
            $inventories = $query->paginate(config('common.list_count'));
        } else {
            $inventories = $query->get();
        }
        return $inventories;
    }

    public function getInventory($request)
    { 
        $staffId = UserData()->id;
        $inventories = Inventory::where('is_active', 1)
            ->whereHas('staff', function ($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            })
            ->with(['inventoryable'])
            ->get();
        return $inventories;
    }

    public function createData($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($data['id'])) {
                $data['id'] = null;
            }
            $inventory = Inventory::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            if(isset($data['inventoryable'])){
                $inventoryables = json_decode($data['inventoryable'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for inventoryable.', 400);
                }
                foreach ($inventoryables as $inventoryable) {
                    Inventoryable::updateOrCreate(
                        [
                            'id' => $inventoryable['id'] ?? null,
                        ],
                        [
                            'inventory_id' => $inventory->id,
                            'inventoryable_type' => $inventoryable['inventoryable_type'],
                            'inventoryable_id' => $inventoryable['inventoryable_id'],
                        ]
                    );
                }
            }
            DB::commit();
            return $inventory;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateData(array $data, int $id)
    {
        $inventory = Inventory::find($id);
        if ($inventory) {
            $data = RemoveNullValues($data);
            $inventory->update($data);
        }
        return $inventory;
    }

    public function detail($inventory)
    {
        $inventory->inventoryable = $inventory->inventoryable;
        return $inventory;
    }

    public function deleteData(int $id)
    {
        $inventory = Inventory::find($id);
        if ($inventory) {
            $inventory->is_active = 0;
            $inventory->save();
        }

        return $inventory;
    }

    public function getInventoryLedgers(int $inventoryId, $request)
    {
        $ledgers = (new GetInventoryStockAction($inventoryId))->run($request);
        return $ledgers;
    }

    public function getInventoryLedgerList($request)
    {
        // $inventoryId = UserData()->department->inventory->inventory_id;
        $inventoryId=$request->inventory_id;
        $ledgers = (new GetInventoryStockAction($inventoryId))->run($request);
        return $ledgers;
    }

    public function inventoryList()
    {
        $inventoryId = UserData()->inventories->pluck('id')->toArray();
        $toInventory = Inventory::where('is_active', 1)
            ->whereNotIn('id', $inventoryId)
            ->get();
        return [
            'source_inventories' => UserData()->inventories,
            'destination_inventories' => $toInventory,
        ];
    }

    public function getallInventories($request)
    {
        $query = Inventory::where('is_active', 1)
            ->with(['inventoryable']);
        if ($request->per_page || $request->page) {
            $inventories = $query->paginate(config('common.list_count'));
        } else {
            $inventories = $query->get();
        }
        return $inventories;
    }

    public function createInventoryItem($validatedData)
    {

        DB::beginTransaction();
        try {

            $minQty = ((int) $validatedData['base_uom_min_quantity'] * (int) $validatedData['conversion']) + (int) $validatedData['uom_min_quantity'];
            $inventoryitem = InventoryItem::updateOrCreate(
                [
                    'inventory_id' => $validatedData['inventory_id'],
                    'item_id' => $validatedData['item_id'],
                ],
                [
                    'base_uom_id' => $validatedData['base_uom_id'],
                    'base_uom_min_quantity' => $validatedData['base_uom_min_quantity'],
                    'uom_id' => $validatedData['uom_id'],
                    'uom_min_quantity' => $validatedData['uom_min_quantity'],
                    'min_quantity' => $minQty
                ]
            );
            DB::commit();
            return ResponseData($inventoryitem, 201, true, 'InventoryItem created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getInventoryItemsByStaff($areaId)
    {
        $staffId = UserData()->id;
        $inventoryId = $this->getInventory($staffId)->pluck('id')->first();
        $inventoryItems = InventoryLedgerItem::with([
            'inventory_ledger',
            'inventory_ledger.inventory.inventoryable.inventoryable',
            'item'
        ])
        ->whereHas('inventory_ledger', function($query) use ($inventoryId) {
            $query->where('inventory_id', $inventoryId);
        })
        ->whereHas('inventory_ledger.inventory.inventoryable', function($query) use ($areaId) {
            $query->where('inventoryable_type', 'area')
                    ->where('inventoryable_id', $areaId);
        })
        ->get();

        if($inventoryItems->isEmpty()){
            return ResponseData([], 404, false, 'AreaEquipments not found.');
        }

        $result = $inventoryItems->groupBy('item_id')
        ->map(function ($items, $itemId) {
            $firstItem = $items->first();
            $item = $firstItem->item;
            $conversion =  $item->conversion;
            $baseUom =  $item->base_uom_name;
            $uom =  $item->item_uom;
            $inQuantity = $items->filter(function ($item) {
                return $item->inventory_ledger->action === "in";
            })->sum('quantity');
            
            $outQuantity = $items->filter(function ($item) {
                return $item->inventory_ledger->action === "out";
            })->sum('quantity');
            $currentQuantity = max(0, $inQuantity - $outQuantity);

            $baseQuantity =  floor($currentQuantity / $conversion);
            $uomQuantity = $currentQuantity % $conversion;
            // $areaInventoryable = collect($firstItem->inventory_ledger->inventory->inventoryable)
            //     ->firstWhere('inventoryable_type', 'area');
            // $area = $areaInventoryable ? $areaInventoryable->inventoryable : null;
            
            return [
                'id' => $firstItem->id,
                'item_id' => $itemId,
                'item_name' => $item->name,
                'base_quantity' =>  $baseQuantity,
                'base_uom' => $baseUom,
                'uom_quantity' => $uomQuantity,
                'uom' => $uom,
            ];
        })
        ->values();
        
    return $result;
    }
}
