<?php

namespace App\Repositories\AssetItemEquipmentAssign;

use App\Models\Staff;
use App\Models\StaffEquipment;
use App\Models\AssetItemAssign;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use Illuminate\Support\Facades\Log;
use App\Models\StaffEquipmentAssign;
use App\Models\StaffEquipmentHandover;
use App\Models\StaffEquipmentHandoverItem;

class AssetItemEquipmentAssignRepository implements AssetItemEquipmentAssignRepositoryInterface
{

  public function getAssetAssigns($request)
  {
    $assetAssigns = AssetItemAssign::with(['staff.roles', 'staff.department', 'assetItem'])
      ->orderBy('id', 'desc');
    return (isset(request()->per_page) || isset(request()->page))
      ? $assetAssigns->paginate(config('common.list_count'))
      : $assetAssigns->get();
    // return $assetAssigns;
  }

  public function createAssetAssign(array $data)
  {
    DB::beginTransaction();
    try {
      $existingAssign = AssetItemAssign::where('staff_id', $data['staff_id'])
        ->where('asset_item_id', $data['asset_item_id'])
        ->first();
      if ($existingAssign) {
        ResponseMessage('Asset item already assigned to this staff', 400);
        return;
      }
      $assetItem = AssetItemAssign::create($data);
      DB::commit();
      ResponseData($assetItem);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function createEquipmentAssign(array $data)
  {
    DB::beginTransaction();
    try {
      $source_inventory_id = UserData()->department->inventory->inventory_id;
      if (!isset($data['staff_id']) || !isset($data['equipments'])) {
        ResponseMessage('Staff ID and equipments are required.', 400);
        return;
      }
      $assignStaff = Staff::findOrFail($data['staff_id']);
      $destination_inventory_id = $assignStaff->department->inventory->inventory_id;

      $staffEquip = StaffEquipment::updateOrCreate(['staff_id' => $data['staff_id']]);
      $equipments = json_decode($data['equipments'], true);
      if (json_last_error() !== JSON_ERROR_NONE) {
        return ResponseMessage('Invalid JSON data provided for equipments.', 400);
      }
      foreach ($equipments as $equipment) {
        // $checkExist = StaffEquipmentAssign::where('staff_equipment_id', $staffEquip->id)
        //   ->where('item_id', $equipment['item_id'])
        //   ->first();
        // if ($checkExist) {
        //   ResponseMessage('Equipment item already assigned to this staff', 400);
        // }
        $uom_quantity = null;
        $equipAssign = null;
        if (isset($equipment['uom_type']) && $equipment['uom_type'] == "base_uom") {
          $uom_quantity = $equipment['quantity'] * $equipment['uom_conversion'];
        } else {
          $uom_quantity = $equipment['quantity'];
        }

        $balanceQuantity = $this->checkInventoryStockEnough($equipment['item_id'], $source_inventory_id, $uom_quantity);
        if ($balanceQuantity === false) {
          ResponseMessage('Insufficient item quantity available.', 400);
        }

        $equipAssign = StaffEquipmentAssign::updateOrCreate(
          [
            'id' => $equipment['id'] ?? null,
            'item_id' => $equipment['item_id'],
          ],
          [
            'equipment_typeable_id' => $staffEquip->id,
            'equipment_typeable_type' => 'staff_equipment',
            'item_id' => $equipment['item_id'],
            'uom_id' => $equipment['uom_id'] ?? null,
            'uom_quantity' => $uom_quantity,
            'quantity' => $equipment['quantity'],
            'uom_type' => $equipment['uom_type']
          ]
        );

        $batchDeductions = $this->processInventoryBatchDeductions($equipment['item_id'], $source_inventory_id, $destination_inventory_id, $staffEquip->id, 'staff_equipment', $uom_quantity);
        if (!$batchDeductions) {
          ResponseMessage('Failed to process inventory batch deductions.', 400);
        }
      }

      DB::commit();
      ResponseData($staffEquip);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getEquipmentAssigns()
  {
    $equipmentAssigns = StaffEquipment::with(['staff.roles', 'staff.department', 'equipmentTypeable','equipmentTypeable.item','equipmentTypeable.uom'])
      ->orderBy('id', 'desc');
    return (isset(request()->per_page) || isset(request()->page))
      ? $equipmentAssigns->paginate(config('common.list_count'))
      :$equipmentAssigns->get();
  }

  public function getEquipmentAssignsByStaffId($request)
  {
    $staff_id = UserData()->id;
    // Calculate current equipment using: staff_equipment + handover_in - handover_out - lost_items
    $currentEquipment = DB::select("
      SELECT 
        i.id as item_id,
        i.name as item_name,
        i.code as item_code,
        u.name as uom_name,
        base_uom.name as base_uom_name,
        base_uom.id as base_uom_id,
        sea.uom_id,
        COALESCE(uc.conversion, 1) as uom_conversion,
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
      LEFT JOIN uoms u ON i.uom_id = u.id
      LEFT JOIN uoms base_uom ON i.base_uom_id = base_uom.id
      LEFT JOIN uom_conversions uc ON uc.item_id = i.id AND uc.base_unit_id = i.base_uom_id AND uc.conversion_unit_id = sea.uom_id AND uc.is_active = 1
      LEFT JOIN staff_equipment se ON sea.equipment_typeable_id = se.id AND sea.equipment_typeable_type = 'staff_equipment'
      LEFT JOIN staff_equipment_handovers seh ON sea.equipment_typeable_id = seh.id AND sea.equipment_typeable_type = 'staff_equipment_handover'
      LEFT JOIN staff_equipment_handover_items seh_item ON seh_item.staff_equipment_handover_id = seh.id AND seh_item.item_id = sea.item_id
      LEFT JOIN lost_items li ON sea.equipment_typeable_id = li.id AND sea.equipment_typeable_type = 'lost_item'
      WHERE (
        (sea.equipment_typeable_type = 'staff_equipment' AND se.staff_id = ?) OR
        (sea.equipment_typeable_type = 'staff_equipment_handover' AND (seh.to_staff_id = ? OR seh.from_staff_id = ?) AND seh.status = 'confirmed' AND seh_item.type = 'personal_equipment') OR
        (sea.equipment_typeable_type = 'lost_item' AND li.staff_id = ? AND li.type = 'personal_equipment')
      )
      GROUP BY i.id, i.name, i.code, u.name,base_uom.name,base_uom.id,sea.uom_id,uc.conversion
      HAVING current_quantity > 0
      ORDER BY i.name
    ", [$staff_id, $staff_id, $staff_id, $staff_id, $staff_id, $staff_id, $staff_id, $staff_id]);

    return collect($currentEquipment);
  }

//   public function getEquipmentAssignsByStaffId($request)
//   {
//     $staff_id = UserData()->id;
//     $handoverActionInSubquery = DB::table('inventory_ledger_items')
//       ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
//       ->join('staff_equipment_handovers', 'inventory_ledgers.ledgerable_id', '=', 'staff_equipment_handovers.id')
//       ->join('staff_equipment_handover_items', 'staff_equipment_handover_items.staff_equipment_handover_id', '=', 'staff_equipment_handovers.id')
//       ->where('staff_equipment_handovers.to_staff_id', $staff_id)
//       ->where('inventory_ledgers.action', 'in')
//       ->where('inventory_ledgers.ledgerable_type', 'staff_equipment_handover')
//       ->select(
//         'staff_equipment_handover_items.item_id',
//         DB::raw('SUM(inventory_ledger_items.quantity) as handover_quantity')
//       )
//       ->groupBy('staff_equipment_handover_items.item_id'); //handover qty for receiver staff 2

//     $inventoryItems = InventoryLedgerItem::join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
//       ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
//       ->leftJoin('uoms', 'items.uom_id', '=', 'uoms.id')
//       ->leftJoin('uoms as base_uom', 'items.base_uom_id', '=', 'base_uom.id')
//       ->leftJoin('staff_equipment_handovers', 'inventory_ledgers.ledgerable_id', '=', 'staff_equipment_handovers.id')
//       ->leftJoin('staff_equipment', 'inventory_ledgers.ledgerable_id', '=', 'staff_equipment.id')
//       ->leftJoin('staff_equipment_assigns', 'staff_equipment_assigns.equipment_typeable_id', '=', 'staff_equipment.id')
//       ->leftJoin('uom_conversions', function ($join) {
//         $join->on('uom_conversions.base_unit_id', '=', 'items.base_uom_id')
//           ->on('uom_conversions.conversion_unit_id', '=', 'items.uom_id')
//           ->on('uom_conversions.item_id', '=', 'items.id')
//           ->where('uom_conversions.is_active', '=', 1);
//       })
//       ->leftJoinSub($handoverActionInSubquery, 'handover_action_in', function ($join) {
//         $join->on('items.id', '=', 'handover_action_in.item_id');
//       })
//       ->where('staff_equipment_assigns.equipment_typeable_type', 'staff_equipment')
//       ->select(
//         'items.id as item_id',
//         'items.name as item_name',
//         'staff_equipment_assigns.id as staff_equipment_assign_id',
//         'uoms.id as uom_id',
//         'uoms.name as uom_name',
//         'base_uom.id as base_uom_id',
//         'base_uom.name as base_uom_name',
//         'staff_equipment_assigns.uom_type',
//         'staff_equipment.staff_id as staff_id',
//         // 'inventory_ledger_items.quantity',
//         // 'inventory_ledgers.action',
//         'uom_conversions.conversion as uom_conversion',
//         // DB::raw('MAX(staff_equipment_assigns.id) as staff_equipment_assign_id'),
//         // DB::raw('MAX(staff_equipment.staff_id) as staff_id'),
//         // DB::raw('MAX(staff_equipment_assigns.uom_type) as uom_type'),
//         DB::raw('
//     SUM(CASE 
//         WHEN inventory_ledgers.ledgerable_type = "staff_equipment_handover"
//         AND inventory_ledgers.action = "out"
//         AND staff_equipment_handovers.from_staff_id = ' . $staff_id . '
//         THEN inventory_ledger_items.quantity
//         ELSE 0
//     END) as handover_quantity  
// ')
//       )
//       ->selectRaw('
//     (
//         (
//             SUM(CASE 
//                 WHEN inventory_ledgers.ledgerable_type = "staff_equipment"
//                 AND inventory_ledgers.action = "in"
//                 AND staff_equipment.staff_id = ' . $staff_id . '
//                 THEN inventory_ledger_items.quantity
//                 ELSE 0
//             END)
//             -
//             SUM(CASE 
//                 WHEN inventory_ledgers.ledgerable_type = "staff_equipment_handover"
//                 AND inventory_ledgers.action = "out"
//                 AND staff_equipment_handovers.from_staff_id = ' . $staff_id . '
//                 THEN inventory_ledger_items.quantity
//                 ELSE 0
//             END)
//         )
//         + COALESCE(handover_action_in.handover_quantity, 0)
//     ) as current_quantity
// ')
//       ->groupBy(
//         'items.id',
//         'items.name',
//         'uoms.id',
//         'uoms.name',
//         'base_uom.id',
//         'base_uom.name',
//         'uom_conversions.conversion'
//       )
//       // ->havingRaw('(handover_quantity > 0 OR current_quantity > 0)')
//       ->get();
//     return $inventoryItems;
//   }

  public function checkInventoryStockEnough($item_id, $inventory_id, $uom_quantity)
  {
    $balanceQuantity = DB::table('inventory_ledger_items')
      ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
      ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
      ->where('inventory_ledger_items.item_id', $item_id)
      ->where('inventory_ledgers.inventory_id', $inventory_id)
      ->select(DB::raw('
              SUM(CASE WHEN inventory_ledgers.action = "in" THEN quantity ELSE 0 END) -
              SUM(CASE WHEN inventory_ledgers.action = "out" THEN quantity ELSE 0 END) as balance_quantity
          '))
      ->first();
    $balanceQuantity = $balanceQuantity->balance_quantity ?? 0;
    if ($uom_quantity > $balanceQuantity) {
      return false;
    }
    return $balanceQuantity;
  }

  public function processInventoryBatchDeductions($item_id, $source_inventory_id, $destination_inventory_id, $ledgerable_id, $ledgerable_type, $uom_quantity)
  {
    $inventoryItems = InventoryLedgerItem::where('inventory_ledger_items.item_id', $item_id)
      ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
      ->where('inventory_ledgers.inventory_id', $source_inventory_id)
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

    $remainingQuantity = $uom_quantity;
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
        'ledgerable_id' => $ledgerable_id,
        'ledgerable_type' => $ledgerable_type,
        'inventory_id' => $source_inventory_id,
        'action' => 'out',
        'batch_no' => $batchDeduction['batch_no'],
      ]);
      $sourceLedger->inventory_ledger_items()->create([
        'item_id' => $item_id,
        'quantity' => $batchDeduction['deduction_amount'],
        'inventory_ledger_id' => $sourceLedger->id,
      ]);
      $destinationLedger = InventoryLedger::create([
        'date' => now(),
        'ledgerable_id' => $ledgerable_id,
        'ledgerable_type' => $ledgerable_type,
        'inventory_id' => $destination_inventory_id,
        'action' => 'in',
        'batch_no' => $batchDeduction['batch_no'],
      ]);
      $destinationLedger->inventory_ledger_items()->create([
        'item_id' => $item_id,
        'quantity' => $batchDeduction['deduction_amount'],
        'inventory_ledger_id' => $destinationLedger->id,
      ]);
    }
    return true;
  }
}
