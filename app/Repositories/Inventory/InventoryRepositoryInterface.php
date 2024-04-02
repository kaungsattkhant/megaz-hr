<?php

namespace App\Repositories\Inventory;

use Illuminate\Http\Request;

use App\Models\Inventory;

interface InventoryRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData($request);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function getInventoryLedgers(int $inventoryId);
}
