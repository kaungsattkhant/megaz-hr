<?php

namespace App\Repositories\Inventory;

use Illuminate\Http\Request;

use App\Models\Inventory;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Inventory::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $inventories = Inventory::where('is_active', 1)->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'inventories');
            $paginationData['inventories'] = $inventories;

            return $paginationData;
        }
        else{
            $inventories = Inventory::where('is_active', 1)->get();

            return $inventories;
        }
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
