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
use App\Jobs\ProcessInventoryJob;
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

    public function createInventoryOpening($request)
    {
        DB::beginTransaction();
        try {
            $date = Carbon::parse($request->date);
            $items=$request->items;
            foreach($items as $itemData){
                $item = Item::find($itemData['item_id']);
                $inventoryId = $itemData['inventory_id'];
                $quantity = ((float)$itemData['base_uom_quantity'] * $item->conversion) + (float)$itemData['uom_quantity'];
                $inventoryLedger = InventoryLedger::create([
                    'inventory_id' => $inventoryId,
                    'date' => $date,
                    'action' => 'in',
                ]);
                $batchNo = now()->format('YmdHis') . '_' . $item->id . '_' . $inventoryLedger->id;
                $inventoryLedger->batch_no = $batchNo;
                $inventoryLedger->save();

                $inventoryLedger->inventory_ledger_items()->create([
                    'inventory_id' => $inventoryId,
                    'item_id' => $item->id,
                    'inventory_ledger_id' => $inventoryLedger->id,
                    'quantity' => $quantity,
                ]);
            }
          
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            ResponseMessage($e->getMessage(), 419);
        }
    }

    public function pushDataInventory($request)
    {

        DB::beginTransaction();
        try {
            if ($request->ip() !== "127.0.0.1") {
                ResponseMessage('Push data is invalid on server', 422);
            }
            $inv = [7]; //local server
            // $inv = [8, 9]; //165 server
            // $inv = [8]; //165 server
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
                // ProcessInventoryJob::dispatch($inventory->id, 100)->delay(now()->addSeconds(2));
                $this->pushPackToInventory($inventory->id, quantity: 20);
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



        $expiredAt = Carbon::now()->addMonth()->endOfMonth();
        $createdBy = UserData()->id;
        $now = now();

        $menus = Menu::where('is_active', 1)
            ->where('id', 306)
            ->get(['id', 'name']);
        DB::transaction(function () use ($menus, $inventoryId, $quantity, $now, $expiredAt, $createdBy) {

            foreach ($menus as $menu) {
                $menuId = $menu->id;

                // 🟢 Step 1: Fetch IN items
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

                if ($menuItems->isEmpty())
                    continue;

                // 🟢 Step 1 Bulk Insert (IN ledgers + items)
                $ledgerData = [];
                $ledgerItemsData = [];

                foreach ($menuItems as $item) {
                    $batchNo = now()->format('YmdHis') . '_' . $item->item_id;

                    $ledgerData[] = [
                        'batch_no' => $batchNo,
                        'inventory_id' => $inventoryId,
                        'date' => $now,
                        'action' => 'in',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                InventoryLedger::insert($ledgerData);

                // Get inserted ledgers
                $insertedLedgers = InventoryLedger::latest('id')
                    ->take(count($ledgerData))
                    ->get()
                    ->reverse() // match order of insert
                    ->values();

                foreach ($menuItems as $index => $item) {
                    $ledgerItemsData[] = [
                        'inventory_ledger_id' => $insertedLedgers[$index]->id,
                        'item_id' => $item->item_id,
                        'quantity' => $item->total_quantity * $quantity,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                InventoryLedgerItem::insert($ledgerItemsData);

                // 🟢 Step 2: Handle ready_to_sale
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

                if ($readyItems->isEmpty())
                    continue;

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

                // 🟢 Step 3: Create Packs in Bulk
                $packData = [];
                for ($i = 0; $i < $quantity; $i++) {
                    $packData[] = [
                        'menu_id' => $menuId,
                        'date' => $now,
                        'expired_at' => $expiredAt,
                        'created_by' => $createdBy,
                        'status' => 'ready',
                        'inventory_id' => $inventoryId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                Pack::insert($packData);
                $packs = Pack::latest('id')->take($quantity)->get()->reverse()->values();

                $packItemsData = [];
                // $ledgerOutData = [];
                // $ledgerOutItemsData = [];

                $ledgerOutData = [];
                $ledgerOutItemsData = [];

                foreach ($packs as $pack) {
                    foreach ($readyItems as $item) {
                        $packItemsData[] = [
                            'pack_id' => $pack->id,
                            'item_id' => $item->item_id,
                            'uom_id' => $item->uom_id,
                            'quantity' => $item->total_quantity,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        $batches = $inventoryData->get($item->item_id, collect());
                        if ($batches->isEmpty()) {
                            throw new \Exception("Not enough stock for item {$item->name} in Menu {$menuId}");
                        }

                        $remainingQty = $item->total_quantity;
                        foreach ($batches as $batch) {
                            if ($remainingQty <= 0)
                                break;
                            $availableQty = (float) $batch->in_stock_quantity;
                            if ($availableQty <= 0)
                                continue;

                            $qtyToTake = min($remainingQty, $availableQty);

                            // build OUT ledger data
                            $ledgerOutData[] = [
                                'batch_no' => $batch->batch_no,
                                'inventory_id' => $inventoryId,
                                'date' => $now,
                                'action' => 'out',
                                'ledgerable_id' => $pack->id,
                                'ledgerable_type' => 'pack',
                                'created_at' => $now,
                                'updated_at' => $now,
                                // optional: temp field to map later
                                '_item_id' => $item->item_id,
                                '_quantity' => $qtyToTake,
                            ];

                            $remainingQty -= $qtyToTake;
                        }
                    }
                }

                PackItem::insert($packItemsData);

                // 🟢 Bulk insert all OUT ledgers
                InventoryLedger::insert(collect($ledgerOutData)->map(fn($d) => collect($d)->except(['_item_id', '_quantity'])->toArray())->toArray());

                // 🟢 Retrieve inserted ledgers
                $insertedOutLedgers = InventoryLedger::latest('id')->take(count($ledgerOutData))->get()->reverse()->values();

                // 🟢 Match them with items
                foreach ($insertedOutLedgers as $index => $ledger) {
                    $ledgerOutItemsData[] = [
                        'inventory_ledger_id' => $ledger->id,
                        'item_id' => $ledgerOutData[$index]['_item_id'],
                        'quantity' => $ledgerOutData[$index]['_quantity'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // 🟢 Bulk insert all OUT ledger items
                InventoryLedgerItem::insert($ledgerOutItemsData);
            }
        });

        // foreach ($menus as $menu) {
        //     $menuId = $menu->id;

        //     // 🟢 Step 1: Create inventory IN ledgers (add stock)
        //     $menuItems = MenuStepItem::join('items', 'menu_step_items.item_id', '=', 'items.id')
        //         ->whereHas('menuStep', fn($q) => $q->where('menu_id', $menuId))
        //         ->select(
        //             'items.id as item_id',
        //             'items.name',
        //             'items.uom_id',
        //             DB::raw('SUM(menu_step_items.quantity) as total_quantity')
        //         )
        //         ->groupBy('items.id', 'items.uom_id', 'items.name')
        //         ->havingRaw('SUM(menu_step_items.quantity) > 0')

        //         ->get();


        //     if ($menuItems->isEmpty()) {
        //         continue;
        //     }

        //     foreach ($menuItems as $item) {
        //         $batchNo = now()->format('YmdHis') . '_' . $item->item_id;

        //         // Create Inventory Ledger (IN)
        //         $ledger = InventoryLedger::create([
        //             'batch_no' => $batchNo,
        //             'inventory_id' => $inventoryId,
        //             'date' => $now,
        //             'action' => 'in',
        //             'created_at' => $now,
        //             'updated_at' => $now,
        //         ]);

        //         // Create Ledger Item
        //         $ledgerItem = $ledger->inventory_ledger_items()->create([
        //             'item_id' => $item->item_id,
        //             'quantity' => $item->total_quantity * $quantity, // add stock
        //             'created_at' => $now,
        //             'updated_at' => $now,
        //         ]);

        //     }

        //     // 🟢 Step 2: Only handle pack creation for "ready_to_sale" steps
        //     $readyItems = MenuStepItem::join('menu_steps', 'menu_step_items.menu_step_id', '=', 'menu_steps.id')
        //         ->join('items', 'menu_step_items.item_id', '=', 'items.id')
        //         ->where('menu_steps.menu_id', $menuId)
        //         ->where('menu_steps.type', 'ready_to_sale')
        //         ->select(
        //             'items.uom_id',
        //             'items.name',
        //             DB::raw('SUM(menu_step_items.quantity) as total_quantity'),
        //             'menu_step_items.item_id'
        //         )
        //         ->groupBy('menu_step_items.item_id', 'items.uom_id', 'items.name')
        //         ->havingRaw('SUM(menu_step_items.quantity) > 0')

        //         ->get();

        //     if ($readyItems->isEmpty()) {
        //         continue; // skip if no ready_to_sale items
        //     }

        //     // 🟢 Step 3: Fetch current inventory stock (IN - OUT)
        //     $itemIds = $readyItems->pluck('item_id');

        //     $inventoryData = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
        //         ->where('inventory_ledgers.inventory_id', $inventoryId)
        //         ->whereIn('inventory_ledger_items.item_id', $itemIds)
        //         ->select(
        //             'inventory_ledger_items.item_id',
        //             'inventory_ledgers.batch_no',
        //             DB::raw("
        //             SUM(CASE WHEN inventory_ledgers.action = 'in' THEN inventory_ledger_items.quantity ELSE 0 END) -
        //             SUM(CASE WHEN inventory_ledgers.action = 'out' THEN inventory_ledger_items.quantity ELSE 0 END)
        //             AS in_stock_quantity
        //         ")
        //         )
        //         ->groupBy('inventory_ledger_items.item_id', 'inventory_ledgers.batch_no')
        //         ->havingRaw('in_stock_quantity > 0')
        //         ->orderBy('inventory_ledgers.created_at', 'asc')
        //         ->get()
        //         ->groupBy('item_id');

        //     // 🟢 Step 4: Create packs and consume batches
        //     for ($i = 0; $i < $quantity; $i++) {
        //         $pack = Pack::create([
        //             'menu_id' => $menuId,
        //             'date' => $now,
        //             'expired_at' => $expiredAt,
        //             'created_by' => $createdBy,
        //             'status' => 'ready',
        //             'inventory_id' => $inventoryId,
        //         ]);

        //         foreach ($readyItems as $item) {
        //             // Create Pack Item
        //             PackItem::create([
        //                 'pack_id' => $pack->id,
        //                 'item_id' => $item->item_id,
        //                 'uom_id' => $item->uom_id,
        //                 'quantity' => $item->total_quantity,
        //             ]);

        //             // Fetch inventory batches for this item
        //             $batches = $inventoryData->get($item->item_id, collect());

        //             if ($batches->isEmpty()) {
        //                 if ($menuId == 72) {
        //                     // dd($inventoryData);
        //                     // dd($readyItems);
        //                 }
        //                 throw new \Exception("Not enough stock for item {$item->name} (ID: {$item->item_id}) in Menu ID {$menuId}");
        //             }

        //             $remainingQty = $item->total_quantity;

        //             foreach ($batches as $batch) {
        //                 if ($remainingQty <= 0)
        //                     break;

        //                 $availableQty = (float) $batch->in_stock_quantity;
        //                 if ($availableQty <= 0)
        //                     continue;

        //                 $qtyToTake = min($remainingQty, $availableQty);

        //                 // Create OUT ledger for pack consumption
        //                 $ledgerOut = InventoryLedger::create([
        //                     'batch_no' => $batch->batch_no,
        //                     'inventory_id' => $inventoryId,
        //                     'date' => $now,
        //                     'action' => 'out',
        //                     'ledgerable_id' => $pack->id,
        //                     'ledgerable_type' => 'pack',
        //                 ]);

        //                 $ledgerOut->inventory_ledger_items()->create([
        //                     'item_id' => $item->item_id,
        //                     'quantity' => $qtyToTake,
        //                 ]);

        //                 $remainingQty -= $qtyToTake;
        //                 $batch->in_stock_quantity -= $qtyToTake;
        //             }

        //             if ($remainingQty > 0) {
        //                 throw new \Exception("Not enough stock to fulfill pack for item {$item->name} in Menu ID {$menuId}");
        //             }
        //         }
        //     }
        // }

    }
}
