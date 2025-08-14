<?php

namespace App\Repositories\AssetItemEquipmentAssign;


interface AssetItemEquipmentAssignRepositoryInterface
{
  public function getAssetAssigns($request);

  public function createAssetAssign(array $data);

  public function createEquipmentAssign(array $data);

  public function getEquipmentAssigns();
}
