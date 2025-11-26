<?php

namespace App\Repositories\Pack;

use App\Models\Item;
use App\Models\Menu;
use App\Models\Pack;
use App\Models\PackItem;
use App\Models\MenuStepItem;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Http\Action\Inventory\StoreInventory;

class PackRepository implements PackRepositoryInterface
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function listAllData(Request $request)
    {
        $inventoryId = !isset($request->inventory_id) ? null : $request->inventory_id;
        $inventoryId = $request->inventory_id ?? null;
        $this->matchInventoryWithPack();
        $query = Pack::with(['menu', 'pack_items'])
            ->when($request->menu_id, fn($q) => $q->where('menu_id', $request->menu_id))
            ->where('inventory_id', $inventoryId);

        return ($request->per_page || $request->page)
            ? $query->paginate(config('common.list_count'))
            : $query->get();
    }


    public function matchInventoryWithPack()
    {
        DB::beginTransaction();
        try {
            $packAll = Pack::with('inventory_ledgers')->whereNull('inventory_id')->get();
            foreach ($packAll as $pack) {
                if ($pack->inventory_ledgers->isNotEmpty()) {
                    foreach ($pack->inventory_ledgers as $ledger) {
                        $pack->inventory_id = $ledger->inventory_id;
                        $pack->save();
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseMessage($e->getMessage(), 402);
        }
    }

    public function createPack(array $data)
    {
        return $this->optimizePackCreate($data);
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $inventoryId = $data['inventory_id'];
            $quantity = $data['quantity'];

            $menu = Menu::where('is_active', 1)->find($data['menu_id']);
            if (!$menu) {
                ResponseMessage('Menu is invalid', 419);
            }
            $menuId = $data['menu_id'];
            $this->orderService->checkInventoryEnough($menuId, $inventoryId, $quantity);

            $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
                ->whereHas('menuStep', function ($q) use ($menuId) {
                    $q->where('menu_id', $menuId)
                        ->where('type', 'ready_to_sale');
                })
                ->select('items.uom_id', 'items.name', DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'), 'menu_step_items.item_id')
                ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
                ->get();
            $pack_arrs = [];
            for ($i = 0; $i < $data['quantity']; $i++) {
                $pack = Pack::create([
                    'menu_id' => $data['menu_id'],
                    'date' => CurrentTime(),
                    'expired_at' => $data['expired_at'],
                    'created_by' => $data['created_by'],
                    'status' => 'ready',
                ]);
                foreach ($menuStepItemByMenu as $item) {
                    PackItem::create([
                        'pack_id' => $pack->id,
                        'item_id' => $item->item_id,
                        'uom_id' => $item->uom_id,
                        'quantity' => $item->total_quantity
                    ]);
                    $inventoryItems = InventoryLedgerItem::where('inventory_ledger_items.item_id', $item->item_id)
                        ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                        ->where('inventory_ledgers.inventory_id', $inventoryId)
                        ->select(
                            'inventory_ledger_items.item_id',
                            'inventory_ledgers.batch_no'
                        )
                        ->selectRaw("
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) as in_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as out_quantity,
        SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) - 
        SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity
    ")
                        ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
                        ->orderBy('inventory_ledgers.created_at', 'asc')
                        ->get();

                    $totalOutQuantity = $item->total_quantity; // e.g. 15000
                    $remainingQuantity = $totalOutQuantity;
                    $array = [];
                    foreach ($inventoryItems as $inventory_item) {
                        if ($remainingQuantity <= 0) {
                            break;
                        }

                        $availableQty = (float) $inventory_item->in_stock_quantity;
                        // Determine how much to take from this batch
                        if ($availableQty <= 0) {
                            continue;
                        }
                        $quantityToTake = min($remainingQuantity, $availableQty);
                        $inventoryLedger = InventoryLedger::create([
                            'batch_no' => $inventory_item->batch_no,
                            'date' => now(),
                            'ledgerable_id' => $pack->id,
                            'ledgerable_type' => 'pack',
                            'inventory_id' => $inventoryId,
                            'action' => 'out',//out
                        ]);

                        $ledgerItem = $inventoryLedger->inventory_ledger_items()->create([
                            'item_id' => $item->item_id,
                            'quantity' => $quantityToTake,
                            'inventory_ledger_id' => $inventoryLedger->id,
                        ]);
                        $remainingQuantity -= $quantityToTake;
                        $array[] = $quantityToTake;
                    }


                }
            }
            DB::commit();
            return ResponseMessage("Packing successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseMessage($e->getMessage(), 402);
        }
    }

    public function optimizePackCreate($data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $inventoryId = $data['inventory_id'];
            $quantity = $data['quantity'];

            $menu = Menu::where('is_active', 1)->find($data['menu_id']);
            if (!$menu) {
                ResponseMessage('Menu is invalid', 419);
            }

            $menuId = $data['menu_id'];
            $this->orderService->checkInventoryEnough($menuId, $inventoryId, $quantity);

            // Get items required for the menu
            $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
                ->whereHas('menuStep', function ($q) use ($menuId) {
                    $q->where('menu_id', $menuId)
                        ->where('type', 'ready_to_sale');
                })
                ->select(
                    'items.uom_id',
                    'items.name',
                    DB::raw('COALESCE(SUM(menu_step_items.quantity), 0) as total_quantity'),
                    'menu_step_items.item_id'
                )
                ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
                ->get();

            // Get current inventory batches grouped by item_id
            $itemIds = $menuStepItemByMenu->pluck('item_id');
            $inventoryData = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                ->where('inventory_ledgers.inventory_id', $inventoryId)
                ->whereIn('inventory_ledger_items.item_id', $itemIds)
                ->select(
                    'inventory_ledger_items.item_id',
                    'inventory_ledgers.batch_no',
                    DB::raw("SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) -
                 SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity")
                )
                ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
                ->orderBy('inventory_ledgers.created_at', 'asc')
                ->havingRaw("in_stock_quantity > 0")  // <-- filter only positive stock
                ->get()
                ->groupBy('item_id');


            // Create packs and consume batches
            for ($i = 0; $i < $quantity; $i++) {
                $pack = Pack::create([
                    'menu_id' => $menuId,
                    'date' => now(),
                    'expired_at' => $data['expired_at'],
                    'created_by' => $data['created_by'],
                    'status' => 'ready',
                    'inventory_id' => $inventoryId,
                ]);

                foreach ($menuStepItemByMenu as $item) {
                    PackItem::create([
                        'pack_id' => $pack->id,
                        'item_id' => $item->item_id,
                        'uom_id' => $item->uom_id,
                        'quantity' => $item->total_quantity
                    ]);

                    $batches = $inventoryData->get($item->item_id, collect());
                    if ($batches->isEmpty()) {
                        ResponseMessage("Not enough stock for item {$item->name}", 419);
                    }

                    $remainingQuantity = $item->total_quantity;

                    foreach ($batches as $batch) {
                        if ($remainingQuantity <= 0)
                            break;

                        $availableQty = (float) $batch->in_stock_quantity;
                        if ($availableQty <= 0)
                            continue;

                        $quantityToTake = min($remainingQuantity, $availableQty);

                        // Create ledger for this batch
                        $inventoryLedger = InventoryLedger::create([
                            'batch_no' => $batch->batch_no,
                            'date' => now(),
                            'ledgerable_id' => $pack->id,
                            'ledgerable_type' => 'pack',
                            'inventory_id' => $inventoryId,
                            'action' => 'out',
                        ]);

                        $inventoryLedger->inventory_ledger_items()->create([
                            'item_id' => $item->item_id,
                            'quantity' => $quantityToTake,
                            'inventory_ledger_id' => $inventoryLedger->id,
                        ]);

                        $remainingQuantity -= $quantityToTake;

                        // Reduce in_stock_quantity in memory to avoid negative stock in next iterations
                        $batch->in_stock_quantity -= $quantityToTake;
                    }

                    if ($remainingQuantity > 0) {
                        ResponseMessage("Not enough stock to fulfill pack for item {$item->name}", 419);
                    }
                }
            }

            // dd('end');
            DB::commit();
            ResponseMessage("Packing successfully");
        } catch (\Throwable $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }


    }

    public function changePack($request)
    {
        return $this->changeBatchPack($request);

        DB::beginTransaction();
        try {
            $packIds = $request->pack_ids;
            $menuId = $request->menu_id;
            $quantity = $request->quantity;
            $packs = Pack::with('menu')
                ->whereIn('id', $packIds)
                ->where('expired_at', '>=', now())
                ->whereStatus('ready')
                ->get();
            $inventoryLedgerItem = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', 'inventory_ledgers.id')
                ->select('inventory_ledger_items.item_id', DB::raw('SUM(quantity) as total_quantity'), 'batch_no')
                ->groupBy('item_id', 'batch_no')
                ->get();
            if ($packs->isEmpty()) {
                ResponseMessage('No valid packs found.', 422);
            }

            $menuIds = $packs->pluck('menu_id')->unique();
            $inventoryId = $packs->first()->inventory_id;
            if ($menuIds->count() > 1) {
                ResponseMessage('All selected packs must belong to the same menu.', 422);
            }

            $menuStepItems = MenuStepItem::whereHas('menuStep', function ($q) use ($menuId) {
                $q->where('menu_id', $menuId);
            })
                ->select('item_id', DB::raw('SUM(weight) as total_weight'), DB::raw('SUM(quantity) as total_quantity'), 'uom_id')
                ->groupBy('item_id')
                ->get();
            $inventoryLedgerItem = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', 'inventory_ledgers.id')
                ->select('inventory_ledger_items.item_id', DB::raw('SUM(quantity) as total_quantity'), 'batch_no')
                ->whereIn('inventory_ledgers.ledgerable_id', $packIds)
                ->where('inventory_ledgers.ledgerable_type', 'pack')
                ->groupBy('item_id', 'batch_no')
                ->get();
            // $packItems = PackItem::with('item')->whereIn('pack_id', $packIds)
            //     ->select('item_id', DB::raw('SUM(quantity) as pack_total_quantity'), 'uom_id')
            //     ->groupBy('item_id', 'uom_id')
            //     ->get()->map(function ($packItem) {
            //         $quantity = $packItem->pack_total_quantity;
            //         if ($packItem->item->base_uom_id === $packItem->uom_id) {
            //             $quantity = $packItem->pack_total_quantity * $packItem->item->uom_conversion;
            //         }
            //         return [
            //             'item_id' => $packItem->item_id,
            //             'quantity' => $quantity,
            //         ];
            //     });
            // return $inventoryLedgerItem;
            // $available = collect($inventoryLedgerItem)->pluck('total_quantity', 'item_id');
            $available = collect($inventoryLedgerItem)
                ->groupBy('item_id')
                ->map(function ($batches) {
                    return $batches->map(function ($batch) {
                        return [
                            'batch_no' => $batch->batch_no,
                            'quantity' => $batch->total_quantity,
                        ];
                    })->values();
                });
            // return $available;
            $packEnoughItems = [];
            for ($i = 0; $i < $quantity; $i++) {
                $enough = [];
                foreach ($menuStepItems as $menuStepItem) {
                    $itemId = $menuStepItem->item_id;
                    $requiredQty = $menuStepItem->total_quantity; // required quantity for the pack


                    if (!isset($available[$itemId]) || $available[$itemId]->sum('quantity') < $requiredQty) {
                        $msg = 'You can make only ' . $i . ' pack(s). Stock not enough for ' . $quantity . ' pack(s).';
                        ResponseMessage($msg, 422);
                    }
                    // $availableQty = $available->get($itemId, 0); // available quantity
                    // if ($availableQty < $requiredQty) {
                    //     $msg = 'You can make available for ' . $i . ' pack.Stock is not enough to make ' . $quantity . ' pack';
                    //     ResponseMessage($msg, 422);
                    // }
                    // $remaining = $availableQty - $requiredQty;
                    // $available[$itemId] = $remaining;

                    $batches = $available[$itemId]->map(function ($b) {
                        return ['batch_no' => $b['batch_no'], 'quantity' => (float) $b['quantity']];
                    })->values()->toArray();
                    $batches = $available[$itemId];
                    $remainingToDeduct = $requiredQty;
                    $usedBatches = [];
                    foreach ($batches as $index => $batch) {
                        if ($remainingToDeduct <= 0)
                            break;

                        $deduct = min($batch['quantity'], $remainingToDeduct);
                        $batches[$index]['quantity'] -= $deduct;
                        return $batches;
                        $remainingToDeduct -= $deduct;

                        $usedBatches[] = [
                            'batch_no' => $batch['batch_no'],
                            'deducted' => $deduct,
                            'remaining_in_batch' => $batches[$index]['quantity'],
                        ];
                    }
                    $available[$itemId] = collect($batches);

                    $enough[] = [
                        'item_id' => $itemId,
                        'required' => $requiredQty,
                        'uom_id' => $menuStepItem->uom_id,
                        'total_weight' => $menuStepItem->total_weight,
                        'remaining' => $available[$itemId]

                    ];
                }
                if (!empty($enough)) {
                    $packEnoughItems[] = $enough;
                }
            }
            if (!empty($packEnoughItems)) {
                foreach ($packEnoughItems as $enoughPack) {
                    $pack = Pack::create([
                        'menu_id' => $menuId,
                        'expired_at' => $request->expired_at,
                        'date' => now(),
                        'status' => 'ready',
                        'inventory_id' => $inventoryId,
                    ]);

                    foreach ($enoughPack as $packItem) {

                        $inventoryLedger = InventoryLedger::create([
                            // 'batch_no' => $batch->batch_no,
                            'date' => now(),
                            'ledgerable_id' => $pack->id,
                            'ledgerable_type' => 'pack',
                            'inventory_id' => $inventoryId,
                            'action' => 'out',
                        ]);
                        $batchNo = now()->format('YmdHis') . '_' . $packItem['item_id'] . '_' . $inventoryLedger->id;
                        $inventoryLedger->batch_no = $batchNo;
                        $inventoryLedger->save();
                        $inventoryLedger->inventory_ledger_items()->create([
                            'item_id' => $packItem['item_id'],
                            'quantity' => $packItem['required'],
                            'inventory_ledger_id' => $inventoryLedger->id,
                        ]);
                        $packItem = PackItem::create([
                            'pack_id' => $pack->id,
                            'item_id' => $packItem['item_id'],
                            'uom_id' => $packItem['uom_id'],
                            'quantity' => $packItem['total_weight'],
                        ]);
                    }
                }
            }
            dd('abc');
            DB::commit();
            // return $packItems;

        } catch (\Throwable $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }
    }

    public function changeBatchPack($request)
    {
        DB::beginTransaction();
        try {
            $packIds = $request->pack_ids;
            $menuId = $request->menu_id;
            $quantity = $request->quantity;
            $packs = Pack::with('menu')
                ->whereIn('id', $packIds)
                ->where('expired_at', '>=', now())
                ->whereStatus('ready')
                ->get();

            if ($packs->isEmpty()) {
                ResponseMessage('No valid packs found.', 422);
            }

            $menuIds = $packs->pluck('menu_id')->unique();
            $inventoryId = $packs->first()->inventory_id;
            if ($menuIds->count() > 1) {
                ResponseMessage('All selected packs must belong to the same menu.', 422);
            }
            $inventoryLedgerOfPack = InventoryLedger::whereIn('ledgerable_id', $packIds)
                ->where('ledgerable_type', 'pack')
                ->get();

            $menuStepItems = MenuStepItem::whereHas('menuStep', function ($q) use ($menuId) {
                $q->where('menu_id', $menuId);
            })
                ->select('item_id', DB::raw('SUM(weight) as total_weight'), DB::raw('SUM(quantity) as total_quantity'), 'uom_id')
                ->groupBy('item_id')
                ->get();
            $inventoryLedgerItem = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', 'inventory_ledgers.id')
                ->select('inventory_ledger_items.item_id', DB::raw('SUM(quantity) as total_quantity'), 'batch_no')
                ->whereIn('inventory_ledgers.ledgerable_id', $packIds)
                ->where('inventory_ledgers.ledgerable_type', 'pack')
                ->groupBy('item_id', 'batch_no')
                ->get();

            $available = collect($inventoryLedgerItem)
                ->groupBy('item_id')
                ->mapWithKeys(function ($batches, $itemId) {
                    // convert each batch to plain array and include quantity as float
                    $arr = $batches->map(function ($b) {
                        return [
                            'batch_no' => $b->batch_no,
                            'quantity' => (float) $b->total_quantity,
                        ];
                    })->values()->toArray(); // ->toArray() ensures plain array inside
                    return [$itemId => $arr];
                })->toArray(); // full structure is plain nested arrays now

            $packEnoughItems = [];

            for ($i = 0; $i < $quantity; $i++) {
                $enough = [];

                foreach ($menuStepItems as $menuStepItem) {
                    $itemId = $menuStepItem->item_id;
                    $requiredQty = (float) $menuStepItem->total_quantity;

                    // total available across batches for this item
                    $totalAvailable = isset($available[$itemId]) ? array_sum(array_column($available[$itemId], 'quantity')) : 0;

                    if ($totalAvailable < $requiredQty) {
                        // cannot make more packs, return message indicating how many we've already made
                        $msg = 'You can make available for ' . $i . ' pack(s). Stock not enough to make ' . $quantity . ' pack(s).';
                        ResponseMessage($msg, 422);
                    }

                    // Deduct from batches (FIFO): operate on plain array
                    $remainingToDeduct = $requiredQty;
                    $usedBatches = [];

                    foreach ($available[$itemId] as $idx => $batch) {
                        if ($remainingToDeduct <= 0)
                            break;

                        $deduct = min($batch['quantity'], $remainingToDeduct);

                        // safe mutation on plain array
                        $available[$itemId][$idx]['quantity'] = $batch['quantity'] - $deduct;
                        $usedBatches[] = [
                            'batch_no' => $batch['batch_no'],
                            'deducted' => $deduct,
                            'remaining_in_batch' => $available[$itemId][$idx]['quantity'],
                        ];
                        $remainingToDeduct -= $deduct;
                    }


                    // after loop, remainingToDeduct must be 0 because we checked totalAvailable earlier
                    $enough[] = [
                        'item_id' => $itemId,
                        'required' => $requiredQty,
                        'used_batches' => $usedBatches,
                        'uom_id' => $menuStepItem->uom_id,
                        'total_weight' => $menuStepItem->total_weight,
                    ];
                }

                // pack successfully prepared
                $packEnoughItems[] = $enough;
            }
            // At this point $packEnoughItems contains per-pack used_batches with batch_no & deducted amounts
            if (!empty($packEnoughItems)) {
                foreach ($packEnoughItems as $enoughPack) {
                    $pack = Pack::create([
                        'menu_id' => $menuId,
                        'expired_at' => $request->expired_at,
                        'date' => now(),
                        'status' => 'ready',
                        'inventory_id' => $inventoryId,
                    ]);

                    foreach ($enoughPack as $packItem) {
                        foreach ($packItem['used_batches'] as $batchUsage) {
                            $inventoryLedger = InventoryLedger::create([
                                'batch_no' => $batchUsage['batch_no'],
                                'date' => now(),
                                'ledgerable_id' => $pack->id,
                                'ledgerable_type' => 'pack',
                                'inventory_id' => $inventoryId,
                                'action' => 'out',
                            ]);

                            $inventoryLedger->inventory_ledger_items()->create([
                                'item_id' => $packItem['item_id'],
                                'quantity' => $batchUsage['deducted'],
                                'inventory_ledger_id' => $inventoryLedger->id,
                            ]);
                        }

                        PackItem::create([
                            'pack_id' => $pack->id,
                            'item_id' => $packItem['item_id'],
                            'uom_id' => $packItem['uom_id'],
                            'quantity' => $packItem['total_weight'],
                        ]);
                    }
                }
            }
            $leftItems = $available;
            foreach ($leftItems as $itemId => $batches) {
                foreach ($batches as $batch) {
                    if ($batch['quantity'] <= 0) {
                        continue; // skip empty batches
                    }

                    $datetimePart = substr($batch['batch_no'], 0, 14);
                    $dateTime = \Carbon\Carbon::createFromFormat('YmdHis', $datetimePart);

                    $inventoryLedger = InventoryLedger::create([
                        'batch_no' => $batch['batch_no'],
                        'date' => $dateTime,
                        'inventory_id' => $inventoryId,
                        'action' => 'in',
                    ]);

                    $inventoryLedger->inventory_ledger_items()->create([
                        'item_id' => $itemId,
                        'quantity' => $batch['quantity'],
                        'inventory_ledger_id' => $inventoryLedger->id,
                    ]);
                }
            }
            $ledgerIds = $inventoryLedgerOfPack->pluck('id');
            InventoryLedger::whereIn('id', $ledgerIds)->delete();
            PackItem::whereIn('pack_id', $packIds)->delete();
            Pack::whereIn('id', $packIds)->delete();
            DB::commit();
            ResponseMessage('Change pack successfully',200);
        } catch (\Throwable $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }
    }

}
