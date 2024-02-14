<?php

namespace App\Repositories\Inventory;

use Illuminate\Http\Request;

use App\Models\Inventory;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->all_minified){
            return Inventory::all();
        }
        $allInventories = Inventory::with('inventoryable')->get();
        $inventories = Pagination($allInventories,$request,'inventories');
        return $inventories;
    }

    public function createData(array $data)
    {
        $inventory = Inventory::create($data);
        return $inventory;
    }

    public function updateData(array $data, int $id)
    {

        $inventory = Inventory::find($id);
        if($inventory)
        {
            $data = RemoveNullValues($data);
            $inventory->update($data);
        }
        return $inventory;
    }

    public function deleteData(int $id)
    {
        $inventory = Inventory::find($id);
        if($inventory){
            $inventory->is_active = 0;
            $inventory->save();
        }

        return $inventory;
    }
}
