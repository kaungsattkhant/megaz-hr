<?php
namespace App\Repositories\StaffEquipmentHandover;

use App\Models\Staff;
use App\Models\StaffEquipment;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Models\StaffEquipmentAssign;
use App\Models\StaffEquipmentHandover;
use App\Models\StaffEquipmentHandoverItem;
use App\Http\Action\SendNotification\SendNotification;
use App\Repositories\StaffEquipmentHandover\StaffEquipmentHandoverRepositoryInterface;

class StaffEquipmentHandoverRepository implements StaffEquipmentHandoverRepositoryInterface
{
    use SendNotification;
    public function createStaffEquipmentHandover(array $data)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['from_staff_id']) || !isset($data['to_staff_id']) || !isset($data['handover_items'])) {
                ResponseMessage('From staff, to staff, and handover items are required.', 400);
                return;
            }
            
            $fromStaffEquipment = StaffEquipment::where('staff_id', $data['from_staff_id'])->first();
            if (!$fromStaffEquipment) {
                ResponseMessage('The source staff has no equipment assigned.', 400);
                return;
            }
            
            $handover = StaffEquipmentHandover::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                ],
                [
                'from_staff_id' => $data['from_staff_id'],
                'to_staff_id' => $data['to_staff_id'],
                'staff_timeshift_id' => $data['staff_timeshift_id'] ?? null,
                'handover_date' => $data['handover_date'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'created_by' => UserData()->id,
            ]);
            
            $handover_items = json_decode($data['handover_items'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                ResponseMessage('Invalid JSON data provided for items.', 400);
                return;
            }
            
            foreach ($handover_items as $handover_item) {
                $staffEquipAssign = StaffEquipmentAssign::where('staff_equipment_id', $fromStaffEquipment->id)
                    ->where('item_id', $handover_item['item_id'])
                    ->first();
                
                if (!$staffEquipAssign) {
                    ResponseMessage('Item not found in source staff equipment.', 400);
                    DB::rollBack();
                    return;
                }
                
                if ($staffEquipAssign->quantity < $handover_item['quantity']) {
                    ResponseMessage('Insufficient quantity available for handover.', 400);
                    DB::rollBack();
                    return;
                }
                
                $uom_quantity = null;
                if (isset($handover_item['uom_type']) && $handover_item['uom_type'] == "base_uom") {
                    $uom_quantity = $handover_item['quantity'] * $handover_item['uom_conversion'];
                } else {
                    $uom_quantity = $handover_item['quantity'];
                }
                
                StaffEquipmentHandoverItem::create([
                    'staff_equipment_handover_id' => $handover->id,
                    'item_id' => $handover_item['item_id'],
                    'uom_id' => $handover_item['uom_id'] ?? null,
                    'uom_quantity' => $uom_quantity,
                    'quantity' => $handover_item['quantity'],
                    'uom_type' => $handover_item['uom_type'] ?? null,
                    'notes' => $handover_item['notes'] ?? null
                ]);
            }
            $this->sendHandoverNotificationToStaff($handover);
            $data=[
                'id' => $handover->id,
                'status' => $handover->status
            ];
            DB::commit();
            ResponseData($data);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function confirmHandover($id, array $data)
    {
        DB::beginTransaction();
        try {
            $handover = StaffEquipmentHandover::with('staffEquipmentHandoverItems')->findOrFail($id);
            
            if ($handover->status !== "pending") {
                ResponseMessage('Only pending handovers can be confirmed.', 400);
                return;
            }
            
            $fromStaffEquipmentAssign = StaffEquipmentAssign::whereHas('staffEquipment', function ($query) use ($handover) {
                $query->where('staff_id', $handover->from_staff_id);
            })->first();
            
            $fromStaff = Staff::with('department.inventory')->findOrFail($handover->from_staff_id);
            $toStaff = Staff::with('department.inventory')->findOrFail($handover->to_staff_id);
            $source_inventory_id = $fromStaff->department->inventory->inventory_id;
            $destination_inventory_id = $toStaff->department->inventory->inventory_id;
            
            foreach ($handover->staffEquipmentHandoverItems as $handoverItem) {
                    $batchDeductions = $this->processInventoryBatchDeductions(
                        // $handoverItem->item_id, 
                        // $source_inventory_id, 
                        // $destination_inventory_id, 
                        // $handoverItem->id, 
                        // 'staff_equipment_handover_item', 
                        // $handoverItem->uom_quantity,
                        // $fromStaffEquipmentAssign->id
                        [
                            'item_id' => $handoverItem->item_id,
                            'source_inventory_id' => $source_inventory_id,
                            'destination_inventory_id' => $destination_inventory_id,
                            'ledgerable_id' => $handoverItem->id,
                            'ledgerable_type' => 'staff_equipment_handover_item',
                            'uom_quantity' => $handoverItem->uom_quantity,
                            'source_ledgerable_id' => $fromStaffEquipmentAssign->id,
                        ]
                    );
                    
                    if (!$batchDeductions) {
                        ResponseMessage('Failed to process inventory batch deductions.', 400);
                        DB::rollBack();
                        return;
                    }
            }
            
            $handover->update([
                'status' => 'confirmed',
                'confirmed_by' => $data['confirmed_by'],
                'confirmed_at' => now()
            ]);
            $data=[
                'id' => $handover->id,
                'status' => $handover->status
            ];
            $this->sendHandoverNotificationFromStaff($handover);
            DB::commit();
            ResponseData($data);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    private function processInventoryBatchDeductions(array $data)
    {
    $inventoryItems = InventoryLedgerItem::where('inventory_ledger_items.item_id',  $data['item_id'])
        ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
        ->where('inventory_ledgers.inventory_id', $data['source_inventory_id'])
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
        $remainingQuantity = $data['uom_quantity'];
        $batchDeductions = [];

        foreach ($inventoryItems as $item) {
        if ($remainingQuantity <= 0) {
        break;
        }
        if ($item->in_stock_quantity <= 0) {
        continue;
        }
        $deductionAmount = min($remainingQuantity, $item->in_stock_quantity);
        $batchDeductions[] = [
        'batch_no' => $item->batch_no,
        'deduction_amount' => $deductionAmount
        ];
        $remainingQuantity -= $deductionAmount;
    }
    foreach ($batchDeductions as $batchDeduction) {
        $sourceLedger = InventoryLedger::create([
            'date' => now(),
            'ledgerable_id' => $data['source_ledgerable_id'],
            'ledgerable_type' => 'staff_equipment_assign',
            'inventory_id' =>  $data['source_inventory_id'],
            'action' => 'out',
            'batch_no' => $batchDeduction['batch_no'],
        ]);
        $sourceLedger->inventory_ledger_items()->create([
            'item_id' => $data['item_id'],
            'quantity' => $batchDeduction['deduction_amount'],
            'inventory_ledger_id' => $sourceLedger->id,
        ]);
        $destinationLedger = InventoryLedger::create([
            'date' => now(),
            'ledgerable_id' => $data['ledgerable_id'],
            'ledgerable_type' => $data['ledgerable_type'],
            'inventory_id' => $data['destination_inventory_id'],
            'action' => 'in',
            'batch_no' => $batchDeduction['batch_no'],
        ]);
        $destinationLedger->inventory_ledger_items()->create([
            'item_id' => $data['item_id'],
            'quantity' => $batchDeduction['deduction_amount'],
            'inventory_ledger_id' => $destinationLedger->id,
        ]);
    }
    return true;
    }

    public function cancelHandover($id, array $data)
    {
        DB::beginTransaction();
        try {
            $handover = StaffEquipmentHandover::with('staffEquipmentHandoverItems')->where('status', 'pending')->findOrFail($id);
            
            $handover->update([
                'status' => 'cancelled',
                'cancelled_by' => $data['cancelled_by'],
                'cancelled_at' => now()
            ]);
            $this->sendHandoverNotificationFromStaff($handover);
            $data=[
                'id' => $handover->id,
                'status' => $handover->status
            ];
            DB::commit();
            ResponseData($data);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    
}
