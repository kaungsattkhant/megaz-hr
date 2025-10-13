<?php

namespace App\Repositories\Inventory;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Staff;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Inventoryable;
use App\Models\InventoryItem;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Actions\Inventory\GetInventoryStockAction;
use App\Models\UomConversion;

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
            ->with(['inventoryable'])
            ->orderBy('id', 'desc');
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
                // $query->where('staff_id', $staffId);
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
            if (isset($data['inventoryable'])) {
                $inventoryables = json_decode($data['inventoryable'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for inventoryable.', 400);
                }
                $currentInventoryableIds = collect($inventoryables)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                if ($inventory->exists && !empty($currentInventoryableIds)) {
                    $inventory->inventoryable()
                        ->whereNotIn('id', $currentInventoryableIds)
                        ->delete();
                } elseif ($inventory->exists) {
                    $inventory->inventoryable()->delete();
                }
                foreach ($inventoryables as $inventoryable) {
                    if ($inventoryable['inventoryable_type'] === "department") {
                        if (!isset($inventoryable['id']) || $inventoryable['id'] === null) {
                            $existingDepartment = Inventoryable::where('inventoryable_type', 'department')
                                ->where('inventoryable_id', $inventoryable['inventoryable_id'])
                                ->first();
                            if ($existingDepartment) {
                                DB::rollback();
                                return ResponseMessage('This department already has a inventory association. Only one inventory per department is allowed.', 400);
                            }
                        }

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
                    } else {
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

    //inventory closing means current inventory stock
    public function getInventoryLedgerList($request)
    {
        // $inventoryId = UserData()->department->inventory->inventory_id;
        $inventoryId = $request->inventory_id;
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

    //area equipment lists
    public function getInventoryItemsByStaff($areaId)
    {
        $inventoryId = UserData()->department->inventory->inventory_id;
        $inventoryItems = InventoryLedgerItem::with([
            'inventory_ledger',
            'inventory_ledger.inventory.inventoryable.inventoryable',
            'item'
        ])
            ->whereHas('inventory_ledger', function ($query) use ($inventoryId) {
                $query->where('inventory_id', $inventoryId);
            })
            ->whereHas('inventory_ledger.inventory.inventoryable', function ($query) use ($areaId) {
                $query->where('inventoryable_type', 'area')
                    ->where('inventoryable_id', $areaId);
            })
            ->get();

        if ($inventoryItems->isEmpty()) {
            return [];
        }

        $result = $inventoryItems->groupBy('item_id')
            ->map(function ($items, $itemId) {
                $firstItem = $items->first();
                $item = $firstItem->item;
                $conversion = max(1, $item->uom_conversion); //to prevent division by zero;
                $baseUom = $item->base_uom_name;
                $uom = $item->item_uom;
                $inQuantity = $items->filter(function ($item) {
                    return $item->inventory_ledger->action === "in";
                })->sum('quantity');

                $outQuantity = $items->filter(function ($item) {
                    return $item->inventory_ledger->action === "out";
                })->sum('quantity');
                $currentQuantity = max(0, $inQuantity - $outQuantity);

                $baseQuantity = floor($currentQuantity / $conversion);
                $uomQuantity = $currentQuantity % $conversion;
                // $areaInventoryable = collect($firstItem->inventory_ledger->inventory->inventoryable)
                //     ->firstWhere('inventoryable_type', 'area');
                // $area = $areaInventoryable ? $areaInventoryable->inventoryable : null;
    
                return [
                    // 'id' => $firstItem->id,
                    'item_id' => $itemId,
                    'item_code' => $item->code,
                    'item_name' => $item->name,
                    // 'base_uom_quantity' =>  $baseQuantity,
                    'base_uom_name' => $baseUom,
                    'base_uom_id' => $item->base_uom_id,
                    // 'uom_quantity' => $uomQuantity,
                    'uom_id' => $item->uom_id,
                    'uom_name' => $item->item_uom,
                    'uom_conversion' => $conversion,
                    'current_quantity' => $currentQuantity,
                ];
            })
            ->values();

        return $result;
    }

    public function getInventoryClosingItems($request)
    {
        $inventoryId = null;
        $userData = UserData();
        if (
            $userData && isset($userData->department) && $userData->department &&
            isset($userData->department->inventory) && $userData->department->inventory
        ) {
            $inventoryId = $userData->department->inventory->inventory_id;
        }

        if ($inventoryId === null) {
            ResponseMessage('Login User have no Inventory', 419);
        }

        $ledgers = (new GetInventoryStockAction($inventoryId))->run($request);
        return $ledgers;
    }

    public function pushDataInventory($request)
    {
        DB::beginTransaction();
        try {
            // $inventories = Inventory::whereIn('id', [8,9])->get(); //hot kitchen 555 and bar inventory
            // $items = Item::all();
            // $inventoryLedgersData = [];
            // $inventoryLedgerItemsData = [];
            // $today = Carbon::today();
            // $now = Carbon::now();

            // foreach ($inventories as $inventory) {
            //     foreach ($items as $item) {
            //         $conversionRate = UomConversion::where('item_id', $item->id)->latest()->first();
            //         if (!$conversionRate) {
            //             ResponseMessage('Uom conversion not found for ' . $item->name, 419);
            //         }
            //         $inventoryLedger = InventoryLedger::create([
            //             'inventory_id' => $inventory->id,
            //             'date' => Carbon::now(),
            //             'action' => 'in',
            //         ]);
            //         $batchNo = now()->format('YmdHis') . '_' . $item->id . '_' . $inventoryLedger->id;
            //         $inventoryLedger->batch_no = $batchNo;
            //         $inventoryLedger->save();

            //         $inventoryLedger->inventory_ledger_items()->create([
            //             'inventory_id' => $inventory->id,
            //             'item_id' => $item->id,
            //             'inventory_ledger_id' => $inventoryLedger->id,
            //             'quantity' => 500 * $conversionRate->conversion,
            //         ]);
                
            //     }
            // }

            DB::commit();
            ResponseMessage('Insert successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
