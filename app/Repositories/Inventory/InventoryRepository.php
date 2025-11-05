<?php

namespace App\Repositories\Inventory;

use PDO;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Pack;
use App\Models\Staff;
use App\Models\PackItem;
use App\Models\Inventory;
use App\Models\MenuStepItem;
use Illuminate\Http\Request;
use App\Models\Inventoryable;
use App\Models\InventoryItem;
use App\Models\UomConversion;
use App\Services\OrderService;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use Illuminate\Support\Facades\Log;
use App\Actions\Inventory\GetInventoryStockAction;

class InventoryRepository implements InventoryRepositoryInterface
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
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
        // return $ledgers; //no pagination
        return paginateCollection($ledgers, config('common.list_count'));
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
            if ($request->ip() !== "127.0.0.1") {
                ResponseMessage('Push data is invalid', 422);
            }
            // $inv = []; //local server
            $inv = [2, 8, 9]; //165 server
            $inventories = Inventory::whereIn('id', $inv)->get(); //hot kitchen 555 and bar inventory
            $items = Item::all();
            $inventoryLedgersData = [];
            $inventoryLedgerItemsData = [];
            $today = Carbon::today();
            $now = Carbon::now();

            foreach ($inventories as $inventory) {
                // foreach ($items as $item) {
                //     $conversionRate = UomConversion::where('item_id', $item->id)->latest()->first();
                //     if (!$conversionRate) {
                //         ResponseMessage('Uom conversion not found for ' . $item->name, 419);
                //     }
                //     $inventoryLedger = InventoryLedger::create([
                //         'inventory_id' => $inventory->id,
                //         'date' => Carbon::now(),
                //         'action' => 'in',
                //     ]);
                //     $batchNo = now()->format('YmdHis') . '_' . $item->id . '_' . $inventoryLedger->id;
                //     $inventoryLedger->batch_no = $batchNo;
                //     $inventoryLedger->save();
                //     $inventoryLedger->inventory_ledger_items()->create([
                //         'inventory_id' => $inventory->id,
                //         'item_id' => $item->id,
                //         'inventory_ledger_id' => $inventoryLedger->id,
                //         'quantity' => 100000 * $conversionRate->conversion,
                //     ]);
                // }
                $this->pushPackToInventory($inventory->id, 10);
            }
            DB::commit();
            ResponseMessage('Insert successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function pushPackToInventory($inventoryId, $quantity)
    {

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('inventory_ledgers')->truncate();
        // DB::table('inventory_ledger_items')->truncate();
        // DB::table('packs')->truncate();
        // DB::table('pack_items')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // $quantity = 10;
        // $expiredAt = Carbon::now()->addMonth()->endOfMonth();
        // $createdBy = UserData()->id;
        // $inventoryId = $inventoryId;
        // $now = now();
        // $menus = Menu::where('is_active', 1)->get(['id', 'name']);
        // $uomConversions = UomConversion::select('item_id', 'conversion')->latest()->get()->keyBy('item_id');
        // $items = Item::pluck('name', 'id');
        // // DB::beginTransaction();

        // // try {
        // foreach ($menus as $menu) {
        //     $menuId = $menu->id;

        //     $menuStepItemByMenu = MenuStepItem::join('items', 'menu_step_items.item_id', 'items.id')
        //         ->whereHas('menuStep', fn($q) => $q->where('menu_id', $menuId))
        //         ->select(
        //             'items.uom_id',
        //             'items.name',
        //             DB::raw('SUM(menu_step_items.quantity) as total_quantity'),
        //             'menu_step_items.item_id'
        //         )
        //         ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
        //         ->get();

        //     $inventoryLedgers = [];
        //     $inventoryLedgerItems = [];


        //     foreach ($menuStepItemByMenu as $menuItem) {
        //         $batchNo = now()->format('YmdHis') . '_' . $menuItem->item_id;

        //         $ledger = [
        //             'batch_no' => $batchNo,
        //             'inventory_id' => $inventoryId,
        //             'date' => $now,
        //             'action' => 'in',
        //             'created_at' => $now,
        //             'updated_at' => $now,
        //         ];
        //         $inventoryLedgers[] = $ledger;
        //     }

        //     InventoryLedger::insert($inventoryLedgers);
        //     $ledgerIds = InventoryLedger::latest()->take(count($inventoryLedgers))->pluck('id');

        //     foreach ($menuStepItemByMenu as $index => $menuItem) {

        //         $inventoryLedgerItems[] = [
        //             'item_id' => $menuItem->item_id,
        //             'inventory_ledger_id' => $ledgerIds[$index],
        //             'quantity' => $menuItem->total_quantity * $quantity,
        //             'created_at' => $now,
        //             'updated_at' => $now,
        //         ];
        //     }

        //     InventoryLedgerItem::insert($inventoryLedgerItems);
        //     //pack create
        //     $packs = [];
        //     for ($i = 0; $i < $quantity; $i++) {
        //         $packs[] = [
        //             'menu_id' => $menuId,
        //             'date' => $now,
        //             'expired_at' => $expiredAt,
        //             'created_by' => $createdBy,
        //             'status' => 'ready',
        //             'inventory_id' => $inventoryId,
        //             'created_at' => $now,
        //             'updated_at' => $now,
        //         ];
        //     }

        //     $menuStepItemByMenuOnlyReadyToSale = MenuStepItem::join('menu_steps', 'menu_step_items.menu_step_id', '=', 'menu_steps.id')
        //         ->join('items', 'menu_step_items.item_id', '=', 'items.id')
        //         ->where('menu_steps.type', 'ready_to_sale')
        //         ->where('menu_steps.menu_id', $menuId)
        //         ->select(
        //             'items.uom_id',
        //             'items.name',
        //             DB::raw('SUM(menu_step_items.quantity) as total_quantity'),
        //             'menu_step_items.item_id'
        //         )
        //         ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
        //         ->get();

        //     if ($menuStepItemByMenuOnlyReadyToSale->isNotEmpty()) {

        //         $itemIds = $menuStepItemByMenuOnlyReadyToSale->pluck('item_id');

        //         $inventoryData = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
        //             ->where('inventory_ledgers.inventory_id', $inventoryId)
        //             ->whereIn('inventory_ledger_items.item_id', $itemIds)
        //             ->select(
        //                 'inventory_ledger_items.item_id',
        //                 'inventory_ledgers.batch_no',
        //                 DB::raw("SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) -
        //          SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END) as in_stock_quantity")
        //             )
        //             ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
        //             ->orderBy('inventory_ledgers.created_at', 'asc')
        //             ->havingRaw("in_stock_quantity > 0")  // <-- filter only positive stock
        //             ->get()
        //             ->groupBy('item_id');

        //         if ($menuStepItemByMenuOnlyReadyToSale->isNotEmpty()) {
        //             Pack::insert($packs);
        //             $packIds = Pack::latest()->take($quantity)->pluck('id');

        //             $packItems = [];
        //             foreach ($packIds as $packId) {

        //                 foreach ($menuStepItemByMenuOnlyReadyToSale as $menuItemReadyToSale) {

        //                     $batches = $inventoryData->get($menuItemReadyToSale->item_id, collect());
        //                     if ($batches->isEmpty()) {
        //                         ResponseMessage("Not enough stock for item {$menuItemReadyToSale->name}. Menu Id is {$menuId}", 419);
        //                     }

        //                     $remainingQuantity = $menuItemReadyToSale->total_quantity * $quantity; // ✅ total required

        //                     foreach ($batches as $batch) {
        //                         if ($remainingQuantity <= 0)
        //                             break;

        //                         $availableQty = (float) $batch->in_stock_quantity;
        //                         if ($availableQty <= 0)
        //                             continue;

        //                         $quantityToTake = min($remainingQuantity, $availableQty);

        //                         $inventoryLedger = InventoryLedger::create([
        //                             'batch_no' => $batch->batch_no,
        //                             'date' => now(),
        //                             'ledgerable_id' => null, // no specific pack, global out
        //                             'ledgerable_type' => 'pack',
        //                             'inventory_id' => $inventoryId,
        //                             'action' => 'out',
        //                         ]);

        //                         $inventoryLedger->inventory_ledger_items()->create([
        //                             'item_id' => $menuItemReadyToSale->item_id,
        //                             'quantity' => $quantityToTake,
        //                             'inventory_ledger_id' => $inventoryLedger->id,
        //                         ]);

        //                         $remainingQuantity -= $quantityToTake;
        //                         $batch->in_stock_quantity -= $quantityToTake;
        //                     }

        //                     if ($remainingQuantity > 0) {
        //                         ResponseMessage("Not enough stock to fulfill pack for item {$menuItemReadyToSale->name}", 419);
        //                     }
        //                     $packItems[] = [
        //                         'pack_id' => $packId,
        //                         'item_id' => $menuItemReadyToSale->item_id,
        //                         'uom_id' => $menuItemReadyToSale->uom_id,
        //                         'quantity' => $menuItemReadyToSale->total_quantity,
        //                         'created_at' => $now,
        //                         'updated_at' => $now,
        //                     ];
        //                 }
        //             }

        //             // Single massive insert (in chunks if needed)
        //             foreach (array_chunk($packItems, 1000) as $chunk) {
        //                 PackItem::insert($chunk);
        //             }
        //         }
        //     }



        // }

        $expiredAt = Carbon::now()->addMonth()->endOfMonth();
        $createdBy = UserData()->id;
        $now = now();

        $menus = Menu::where('is_active', 1)->get(['id', 'name']);

        foreach ($menus as $menu) {
            $menuId = $menu->id;

            // 🟢 Step 1: Create inventory IN ledgers (add stock)
            $menuItems = MenuStepItem::join('items', 'menu_step_items.item_id', '=', 'items.id')
                ->whereHas('menuStep', fn($q) => $q->where('menu_id', $menuId))
                ->select(
                    'items.id as item_id',
                    'items.name',
                    'items.uom_id',
                    DB::raw('SUM(menu_step_items.quantity) as total_quantity')
                )
                ->groupBy('items.id', 'items.uom_id', 'items.name')
                ->havingRaw('SUM(menu_step_items.quantity) > 0')

                ->get();


            if ($menuItems->isEmpty()) {
                continue;
            }

            foreach ($menuItems as $item) {
                $batchNo = now()->format('YmdHis') . '_' . $item->item_id;

                // Create Inventory Ledger (IN)
                $ledger = InventoryLedger::create([
                    'batch_no' => $batchNo,
                    'inventory_id' => $inventoryId,
                    'date' => $now,
                    'action' => 'in',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Create Ledger Item
                $ledgerItem = $ledger->inventory_ledger_items()->create([
                    'item_id' => $item->item_id,
                    'quantity' => $item->total_quantity * $quantity, // add stock
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

            }

            // 🟢 Step 2: Only handle pack creation for "ready_to_sale" steps
            $readyItems = MenuStepItem::join('menu_steps', 'menu_step_items.menu_step_id', '=', 'menu_steps.id')
                ->join('items', 'menu_step_items.item_id', '=', 'items.id')
                ->where('menu_steps.menu_id', $menuId)
                ->where('menu_steps.type', 'ready_to_sale')
                ->select(
                    'items.uom_id',
                    'items.name',
                    DB::raw('SUM(menu_step_items.quantity) as total_quantity'),
                    'menu_step_items.item_id'
                )
                ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
                ->havingRaw('SUM(menu_step_items.quantity) > 0')

                ->get();

            if ($readyItems->isEmpty()) {
                continue; // skip if no ready_to_sale items
            }

            // 🟢 Step 3: Fetch current inventory stock (IN - OUT)
            $itemIds = $readyItems->pluck('item_id');

            $inventoryData = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
                ->where('inventory_ledgers.inventory_id', $inventoryId)
                ->whereIn('inventory_ledger_items.item_id', $itemIds)
                ->select(
                    'inventory_ledger_items.item_id',
                    'inventory_ledgers.batch_no',
                    DB::raw("
                    SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) -
                    SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END)
                    AS in_stock_quantity
                ")
                )
                ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
                ->havingRaw('in_stock_quantity > 0')
                ->orderBy('inventory_ledgers.created_at', 'asc')
                ->get()
                ->groupBy('item_id');

            // 🟢 Step 4: Create packs and consume batches
            for ($i = 0; $i < $quantity; $i++) {
                $pack = Pack::create([
                    'menu_id' => $menuId,
                    'date' => $now,
                    'expired_at' => $expiredAt,
                    'created_by' => $createdBy,
                    'status' => 'ready',
                    'inventory_id' => $inventoryId,
                ]);

                foreach ($readyItems as $item) {
                    // Create Pack Item
                    PackItem::create([
                        'pack_id' => $pack->id,
                        'item_id' => $item->item_id,
                        'uom_id' => $item->uom_id,
                        'quantity' => $item->total_quantity,
                    ]);

                    // Fetch inventory batches for this item
                    $batches = $inventoryData->get($item->item_id, collect());

                    if ($batches->isEmpty()) {
                        if ($menuId == 72) {
                            // dd($inventoryData);
                            // dd($readyItems);
                        }
                        throw new \Exception("Not enough stock for item {$item->name} (ID: {$item->item_id}) in Menu ID {$menuId}");
                    }

                    $remainingQty = $item->total_quantity;

                    foreach ($batches as $batch) {
                        if ($remainingQty <= 0)
                            break;

                        $availableQty = (float) $batch->in_stock_quantity;
                        if ($availableQty <= 0)
                            continue;

                        $qtyToTake = min($remainingQty, $availableQty);

                        // Create OUT ledger for pack consumption
                        $ledgerOut = InventoryLedger::create([
                            'batch_no' => $batch->batch_no,
                            'inventory_id' => $inventoryId,
                            'date' => $now,
                            'action' => 'out',
                            'ledgerable_id' => $pack->id,
                            'ledgerable_type' => 'pack',
                        ]);

                        $ledgerOut->inventory_ledger_items()->create([
                            'item_id' => $item->item_id,
                            'quantity' => $qtyToTake,
                        ]);

                        $remainingQty -= $qtyToTake;
                        $batch->in_stock_quantity -= $qtyToTake;
                    }

                    if ($remainingQty > 0) {
                        throw new \Exception("Not enough stock to fulfill pack for item {$item->name} in Menu ID {$menuId}");
                    }
                }
            }
        }

    }
}
