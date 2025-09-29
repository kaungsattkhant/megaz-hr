<?php
namespace App\Repositories\StaffEquipmentHandover;

use App\Models\Staff;
use App\Models\StaffEquipment;
use App\Models\StaffTimeshift;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Models\StaffEquipmentAssign;
use App\Models\StaffEquipmentHandover;
use App\Models\StaffEquipmentHandoverItem;
use App\Http\Action\SendNotification\SendNotification;
use App\Models\LostItem;
use App\Repositories\StaffEquipmentHandover\StaffEquipmentHandoverRepositoryInterface;

class StaffEquipmentHandoverRepository implements StaffEquipmentHandoverRepositoryInterface
{
    use SendNotification;
    public function getHandoverStaffs()
    {
        return Staff::where('id', '!=', UserData()->id)->where('department_id', UserData()->department_id)
        ->where('is_cv', 0)
        ->where('is_active', 1)
        ->get();
    }
    public function getStaffTimeshift($staffId)
    {
        return StaffTimeshift::with('timeshift')->where('staff_id', $staffId)->where('status', 'confirmed')->where('date_time', '>=', now()->toDateString())->get();
    }
    public function createStaffEquipmentHandover(array $data)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['from_staff_id']) || !isset($data['to_staff_id']) || !isset($data['handover_items'])) {
                ResponseMessage('From staff, to staff, and handover items are required.', 400);
                return;
            }

            $fromStaff = Staff::with('department.inventory')->findOrFail($data['from_staff_id']);
            $toStaff = Staff::with('department.inventory')->findOrFail($data['to_staff_id']);
            $source_inventory_id = $fromStaff->department->inventory->inventory_id;
            $destination_inventory_id = $toStaff->department->inventory->inventory_id;
            
            // $fromStaffEquipment = StaffEquipment::where('staff_id', $data['from_staff_id'])->first();
            //handover 
            // if (!$fromStaffEquipment) {
            //     ResponseMessage('The source staff has no equipment assigned.', 400);
            //     return;
            // }
            
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
            
            if(isset($data['handover_items'])){
                $handover_items = json_decode($data['handover_items'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    ResponseMessage('Invalid JSON data provided for handover_items.', 400);
                    return;
                }
                
                foreach ($handover_items as $handover_item) {
                    // if($handover_item['type'] === "personal_equipment"){
                  
                    //     $currentItem = $this->getEquipmentAssignByItemAndStaff($handover_item['item_id'], $data['from_staff_id']);
                    //     $currentQty = (int) $currentItem->current_quantity ?? 0;
                    //     if ($handover_item['quantity'] > $currentQty) {
                    //         ResponseMessage('Insufficient quantity available for handover.', 400);
                    //         DB::rollBack();
                    //         return;
                    //     }
                    // }
                    $uom_quantity = $this->calculateUomQty($handover_item);
                    
                    StaffEquipmentHandoverItem::create([
                        'staff_equipment_handover_id' => $handover->id,
                        'item_id' => $handover_item['item_id'],
                        'uom_id' => $handover_item['uom_id'] ?? null,
                        'uom_quantity' => $uom_quantity,
                        'quantity' => $handover_item['quantity'],
                        'uom_type' => $handover_item['uom_type'] ?? null,
                        'notes' => $handover_item['notes'] ?? null,
                        'type' => $handover_item['type'] ?? null,
                    ]);
                }
            }

            if(isset($data['lost_handover_items'])){
                $lost_handover_items = json_decode($data['lost_handover_items'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    ResponseMessage('Invalid JSON data provided for lost_handover_items.', 400);
                    return;
                }
                foreach ($lost_handover_items as $lost_handover_item) {
                    $uom_quantity = $this->calculateUomQty($lost_handover_item);
                    $lostItem = LostItem::create([
                            'item_id' => $lost_handover_item['item_id'],
                            'uom_id' => $lost_handover_item['uom_id'] ?? null,
                            'uom_quantity' => $uom_quantity,
                            'quantity' => $lost_handover_item['quantity'],
                            'uom_type' => $lost_handover_item['uom_type'] ?? null,
                            'lost_note' => $lost_handover_item['lost_note'] ?? null,
                            'type' => $lost_handover_item['type'] ?? null,
                            'staff_id' => $handover->from_staff_id,
                        ]);

                    if($lost_handover_item['type'] == "personal_equipment"){
                        StaffEquipmentAssign::updateOrCreate([
                                'equipment_typeable_id' => $lostItem->id,
                                'equipment_typeable_type' => 'lost_item',
                            ],[
                                'equipment_typeable_id' => $lostItem->id,
                            'equipment_typeable_type' => 'lost_item',
                            'item_id'=>$lostItem->item_id,
                            'uom_id'=>$lostItem->uom_id,
                            'uom_quantity'=>$lostItem->uom_quantity,
                            'quantity'=>$lostItem->quantity,
                            'uom_type'=>$lostItem->uom_type,
                        ]);
                        $batchDeductions = $this->processInventoryBatchDeductions([
                            'item_id' => $lostItem->item_id,
                            'source_inventory_id' => $source_inventory_id,
                            'destination_inventory_id' => $destination_inventory_id,
                            'ledgerable_id' => $lostItem->id,
                            'ledgerable_type' => $lostItem,
                            'uom_quantity' => $lostItem->uom_quantity,
                        ]);
                    }
                
                    if($lost_handover_item['type'] == "inventory_closing"){
                        $batchDeductions = $this->processInventoryBatchDeductions([
                                            'item_id' => $lostItem->item_id,
                                            'source_inventory_id' => $source_inventory_id,
                                            'destination_inventory_id' => $destination_inventory_id,
                                            'ledgerable_id' => $lostItem->id,
                                            'ledgerable_type' => $lostItem,
                                            'uom_quantity' => $lostItem->uom_quantity,
                                        ]);
                                    if (!$batchDeductions) {
                                        ResponseMessage('Failed to process inventory batch deductions.', 400);
                                        DB::rollBack();
                                        return;
                                    }
                    } 
            }
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
            $fromStaff = Staff::with('department.inventory')->findOrFail($handover->from_staff_id);
            $toStaff = Staff::with('department.inventory')->findOrFail($handover->to_staff_id);
            $source_inventory_id = $fromStaff->department->inventory->inventory_id;
            $destination_inventory_id = $toStaff->department->inventory->inventory_id;
            if ($handover->status !== "pending") {
                ResponseMessage('Only pending handovers can be confirmed.', 400);
                return;
            }
            $handover->update([
                'status' => 'confirmed',
                'confirmed_by' => $data['confirmed_by'],
                'confirmed_at' => now()
            ]);

            $personalEquipmentHandoverItems = $handover->staffEquipmentHandoverItems->where('type', 'personal_equipment');
            //$inventoryClosingHandoverItems = $handover->staffEquipmentHandoverItems->where('type', 'inventory_closing');

            if(isset($personalEquipmentHandoverItems)){
                StaffEquipment::updateOrCreate([
                    'staff_id' => $handover->to_staff_id,
                ],[
                    'staff_id' => $handover->to_staff_id,
                ]);
            foreach ($personalEquipmentHandoverItems as $personalHandoverItem) {
                StaffEquipmentAssign::updateOrCreate([
                'equipment_typeable_id' => $handover->id,
                'equipment_typeable_type' => 'staff_equipment_handover',
                ],[
                    'equipment_typeable_id' => $handover->id,
                    'equipment_typeable_type' => 'staff_equipment_handover',
                    'item_id'=>$personalHandoverItem->item_id,
                    'uom_id'=>$personalHandoverItem->uom_id,
                    'uom_quantity'=>$personalHandoverItem->uom_quantity,
                    'quantity'=>$personalHandoverItem->quantity,
                    'uom_type'=>$personalHandoverItem->uom_type,
                ]);
            }
        }
        // if(isset($inventoryClosingHandoverItems)){
        //     foreach ($inventoryClosingHandoverItems as $inventoryClosingHandoverItem) {
        //         $batchDeductions = $this->processInventoryBatchDeductions([
        //             'item_id' => $inventoryClosingHandoverItem->item_id,
        //             'source_inventory_id' => $source_inventory_id,
        //             'destination_inventory_id' => $destination_inventory_id,
        //             'ledgerable_id' => $inventoryClosingHandoverItem->id,
        //             'ledgerable_type' => 'staff_equipment_handover_item',
        //             'uom_quantity' => $inventoryClosingHandoverItem->uom_quantity,
        //         ]);
        //     }
        // }
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
        $ledgerable_type =  is_string($data['ledgerable_type']) ? $data['ledgerable_type'] : RelationMorphName($data['ledgerable_type']);

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
            'ledgerable_id' => $data['ledgerable_id'],
            'ledgerable_type' => $ledgerable_type,
            'inventory_id' =>  $data['source_inventory_id'],
            'action' => 'out',
            'batch_no' => $batchDeduction['batch_no'],
        ]);
        $sourceLedger->inventory_ledger_items()->create([
            'item_id' => $data['item_id'],
            'quantity' => $batchDeduction['deduction_amount'],
            'inventory_ledger_id' => $sourceLedger->id,
        ]);
        // $destinationLedger = InventoryLedger::create([
        //     'date' => now(),
        //     'ledgerable_id' => $data['ledgerable_id'],
        //     'ledgerable_type' => $ledgerable_type,
        //     'inventory_id' => $data['destination_inventory_id'],
        //     'action' => 'in',
        //     'batch_no' => $batchDeduction['batch_no'],
        // ]);
        // $destinationLedger->inventory_ledger_items()->create([
        //     'item_id' => $data['item_id'],
        //     'quantity' => $batchDeduction['deduction_amount'],
        //     'inventory_ledger_id' => $destinationLedger->id,
        // ]);
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
                'reject_note' => $data['reject_note'],
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

    public function getEquipmentAssignByItemAndStaff($itemId, $staffId)
    {
        $currentEquipment = DB::select("
            SELECT 
                i.id as item_id,
                i.name as item_name,
                i.code as item_code,
                u.name as uom_name,
                base_uom.name as base_uom_name,
                base_uom.id as base_uom_id,
                sea.uom_id,
                COALESCE(uc.conversion, 1) as conversion_unit,
                CAST(SUM(
                    CASE 
                        WHEN sea.equipment_typeable_type = 'staff_equipment' AND se.staff_id = ? THEN sea.uom_quantity
                        WHEN sea.equipment_typeable_type = 'staff_equipment_handover' AND seh.to_staff_id = ? AND seh.status = 'confirmed' THEN sea.uom_quantity
                        WHEN sea.equipment_typeable_type = 'staff_equipment_handover' AND seh.from_staff_id = ? AND seh.status = 'confirmed' THEN -sea.uom_quantity
                        WHEN sea.equipment_typeable_type = 'lost_item' AND li.staff_id = ? THEN -sea.uom_quantity
                        ELSE 0
                    END
                ) AS SIGNED) as current_quantity
            FROM staff_equipment_assigns sea
            JOIN items i ON sea.item_id = i.id
            LEFT JOIN uoms u ON i.base_uom_id = u.id
            LEFT JOIN uoms base_uom ON i.base_uom_id = base_uom.id
            LEFT JOIN uom_conversions uc ON uc.item_id = i.id AND uc.base_unit_id = i.base_uom_id AND uc.conversion_unit_id = sea.uom_id AND uc.is_active = 1
            LEFT JOIN staff_equipment se ON sea.equipment_typeable_id = se.id AND sea.equipment_typeable_type = 'staff_equipment'
            LEFT JOIN staff_equipment_handovers seh ON sea.equipment_typeable_id = seh.id AND sea.equipment_typeable_type = 'staff_equipment_handover'
            LEFT JOIN staff_equipment_handover_items seh_item ON seh_item.staff_equipment_handover_id = seh.id AND seh_item.item_id = sea.item_id
            LEFT JOIN lost_items li ON sea.equipment_typeable_id = li.id AND sea.equipment_typeable_type = 'lost_item'
            WHERE i.id = ? AND (
                (sea.equipment_typeable_type = 'staff_equipment' AND se.staff_id = ?) OR
                (sea.equipment_typeable_type = 'staff_equipment_handover' AND (seh.to_staff_id = ? OR seh.from_staff_id = ?) AND seh.status = 'confirmed' AND seh_item.type = 'personal_equipment') OR
                (sea.equipment_typeable_type = 'lost_item' AND li.staff_id = ? AND li.type = 'personal_equipment')
            )
            GROUP BY i.id, i.name, i.code, u.name, base_uom.name, base_uom.id, sea.uom_id, uc.conversion
            ORDER BY i.name
        ", [$staffId, $staffId, $staffId, $staffId, $itemId, $staffId, $staffId, $staffId, $staffId]);

        return collect($currentEquipment)->first();
    }
    
    public function calculateUomQty(array $data)
    {
        $uom_quantity = null;
        if (isset($data['uom_type']) && $data['uom_type'] == "base_uom") {
            $uom_quantity = $data['quantity'] * $data['uom_conversion'];
        } else {
            $uom_quantity = $data['quantity'];
        }
        return $uom_quantity;
    }

    public function getStaffEquipmentHandoverById($id)
    {
        return StaffEquipmentHandover::with('fromStaff', 'toStaff', 'staffTimeshift', 'staffEquipmentHandoverItems', 'staffEquipmentHandoverItems.item', 'staffEquipmentHandoverItems.uom')->findOrFail($id);
    }

    public function getLostItems()
    {
        return LostItem::with('item', 'uom', 'staff')->paginate(config('common.list_count'));
    }
    
}
