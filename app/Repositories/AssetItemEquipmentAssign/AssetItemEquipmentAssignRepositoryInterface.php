<?php

namespace App\Repositories\AssetItemEquipmentAssign;


interface AssetItemEquipmentAssignRepositoryInterface
{
  public function getAssetAssigns($request);

  public function createAssetAssign(array $data);

  public function createEquipmentAssign(array $data);

  public function getEquipmentAssigns();

  public function checkInventoryStockEnough($item_id, $inventory_id, $uom_quantity);

  public function processInventoryBatchDeductions($item_id, $source_inventory_id, $destination_inventory_id, $ledgerable_id, $ledgerable_type, $uom_quantity);

  public function getEquipmentAssignsByStaffId($request);
}
