<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Inventoryable;
use Illuminate\Support\Facades\DB;
use App\Actions\Inventory\GetInventoryStockAction;
use App\Models\InventoryItem;

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
            ->whereHas('staff', function ($query) {
                $query->where('id', UserData()->id);
            })
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
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $request_inventoryable_id = $request->inventoryable_id;

            $inventory = Inventory::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            if (!isset($request->id)) {
                foreach ($request_inventoryable_id as $id) {
                    $inventoryable = $inventory->inventoryable()->create([
                        'inventoryable_type' => $request->inventoryable_type,
                        'inventoryable_id' => $id,
                    ]);
                }
            } else {
                $inventoryable_id = $inventory->inventoryable->pluck('inventoryable_id')->toArray();
                // $difference=$request_inventoryable_id->diff($inventoryable_id);
                $created_ids = array_diff($request_inventoryable_id, $inventoryable_id);
                $deleted_ids = array_diff($inventoryable_id, $request_inventoryable_id);

                if (count($created_ids)) {
                    // dd('ab');
                    foreach ($created_ids as $id) {
                        $inventoryable = $inventory->inventoryable()->create([
                            'inventoryable_type' => 'department',
                            'inventoryable_id' => $id,
                        ]);
                    }
                }
                if (count($deleted_ids)) {
                    Inventoryable::whereIn('inventoryable_id', $deleted_ids)->where('inventoryable_type', $request->inventoryable_type)->delete();
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
        $toInventory = Inventory::where('is_active', 1)
            ->whereNotIn('id', InventoryIds())
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
}
