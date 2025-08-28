<?php

namespace App\Repositories\Inventory;

use Illuminate\Http\Request;

interface InventoryRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData($request);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function getInventoryLedgers(int $inventoryId, $request);

    public function detail($inventory);

    public function inventoryList();

    public function getInventory($request);

    public function getInventoryLedgerList($request);

    public function createInventoryItem($validatedData);

    public function getallInventories($request);

    public function getInventoryItemsByStaff($areaId);
}
