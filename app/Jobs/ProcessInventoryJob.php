<?php

namespace App\Jobs;


use App\Models\Menu;
use App\Models\MenuStepItem;
use App\Models\InventoryLedger;
use App\Models\InventoryLedgerItem;
use App\Models\Pack;
use App\Models\PackItem;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Exception;

class ProcessInventoryJob implements ShouldQueue
{
    use Queueable;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $inventoryId;
    protected $quantity;

    public function __construct($inventoryId, $quantity)
    {
        $this->inventoryId = $inventoryId;
        $this->quantity = $quantity;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $inventoryId = $this->inventoryId;
        $quantity = $this->quantity;
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
