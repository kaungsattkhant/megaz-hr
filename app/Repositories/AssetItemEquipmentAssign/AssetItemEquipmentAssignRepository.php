<?php

namespace App\Repositories\AssetItemEquipmentAssign;

use App\Models\StaffEquipment;
use App\Models\AssetItemAssign;
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
      $inventoryId = UserData()->department->inventory->inventory_id;
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

        $balanceQuantity = DB::table('inventory_ledger_items')
          ->join('items', 'inventory_ledger_items.item_id', '=', 'items.id')
          ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
          ->where('inventory_ledger_items.item_id', $equipment['item_id'])
          ->where('inventory_ledgers.inventory_id',  $inventoryId)
          ->select(DB::raw('
            SUM(CASE WHEN inventory_ledgers.action = "in" THEN quantity ELSE 0 END) -
            SUM(CASE WHEN inventory_ledgers.action = "out" THEN quantity ELSE 0 END) as balance_quantity
        '))
          ->first();

        $balanceQuantity = $balanceQuantity->balance_quantity ?? 0;

        if ($uom_quantity > $balanceQuantity) {
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
      }
      DB::commit();
      ResponseData($equipAssign);
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
}
