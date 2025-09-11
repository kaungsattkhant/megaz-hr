<?php

namespace App\Repositories\AssetItemEquipmentAssign;

use App\Models\Staff;
use App\Models\StaffEquipment;
use App\Models\AssetItemAssign;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Models\StaffEquipmentAssign;

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
        $checkExist = StaffEquipmentAssign::where('staff_equipment_id', $staffEquip->id)
          ->where('item_id', $equipment['item_id'])
          ->first();
        if ($checkExist) {
          ResponseMessage('Equipment item already assigned to this staff', 400);
        }

        $uom_quantity = null;
        $equipAssign = null;
        if (isset($equipment['uom_type']) && $equipment['uom_type'] == "base_uom") {
          $uom_quantity = $equipment['quantity'] * $equipment['uom_conversion'];
        } else {
          $uom_quantity = $equipment['quantity'];
        }

        $balanceQuantity =  $this->checkInventoryStockEnough($equipment['item_id'], $source_inventory_id, $uom_quantity);
        if ($balanceQuantity === false) {
          ResponseMessage('Insufficient item quantity available.', 400);
        }

        $equipAssign = StaffEquipmentAssign::updateOrCreate(
          [
            'id' => $equipment['id'] ?? null,
          ],
          [
            'staff_equipment_id' => $staffEquip->id,
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
    $equipmentAssigns = StaffEquipment::with(['staff.roles', 'staff.department', 'staffEquipmentAssigns.item', 'staffEquipmentAssigns.baseUom', 'staffEquipmentAssigns.uom'])
      ->orderBy('id', 'desc');
    return (isset(request()->per_page) || isset(request()->page))
      ? $equipmentAssigns->paginate(config('common.list_count'))
      : $equipmentAssigns->get();
  }

  public function getEquipmentAssignsByStaffId($request){
    $staff_id = UserData()->id;
    $equipmentAssigns = StaffEquipmentAssign::with([
      'staffEquipment.staff',
      'item',
      'baseUom',
      'uom'
    ])
      ->whereHas('staffEquipment', function ($query) use ($staff_id) {
        $query->where('staff_id', $staff_id);
      })
      ->orderBy('id', 'desc')->get();
      if($equipmentAssigns->isEmpty()){
        return [];
    }
    return $equipmentAssigns;
  }

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
    $inventoryItems = InventoryLedgerItem::where('inventory_ledger_items.item_id',  $item_id)
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
        'inventory_id' =>  $source_inventory_id,
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
        'ledgerable_type' => 'staff_equipment_assign',
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
