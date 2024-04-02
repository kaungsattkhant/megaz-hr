<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Inventoryable;
use Illuminate\Support\Facades\DB;
use App\Actions\Inventory\GetInventoryStockAction;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Inventory::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $inventories = Inventory::where('is_active', 1)->skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'inventories');
            $paginationData['inventories'] = $inventories;

            return $paginationData;
        } else {
            $inventories = Inventory::where('is_active', 1)->get();

            return $inventories;
        }
    }

    public function createData($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $request_inventoryable_id=$request->inventoryable_id;

            $inventory = Inventory::updateOrCreate(
                ['id' => $data['id']],
                $data   
            );
            if(!isset($request->id)){
                foreach($request_inventoryable_id as $id){
                    $inventoryable=$inventory->inventoryable()->create([
                         'inventoryable_type'=>'department',
                         'inventoryable_id'=>$id,
                     ]);
                 }
            }else{
                $inventoryable_id=$inventory->inventoryable->pluck('inventoryable_id')->toArray();
                // $difference=$request_inventoryable_id->diff($inventoryable_id);
                $created_ids = array_diff($request_inventoryable_id, $inventoryable_id);
                $deleted_ids = array_diff($inventoryable_id, $request_inventoryable_id);

                if(count($created_ids)){
                    // dd('ab');
                    foreach($created_ids as $id){
                        $inventoryable=$inventory->inventoryable()->create([
                             'inventoryable_type'=>'department',
                             'inventoryable_id'=>$id,
                         ]);
                     }
                }
                if(count($deleted_ids)){
                    Inventoryable::whereIn('inventoryable_id',$deleted_ids)->where('inventoryable_type',$request->inventoryable_type)->delete();
                }
            }   
            DB::commit();
            return $inventory;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateData(array $data, int $id)
    {
        $inventory = Inventory::find($id);
        if ($inventory) {
            $data = RemoveNullValues($data);
            $inventory->update($data);
        }
        return $inventory;
    }

    public function deleteData(int $id)
    {
        $inventory = Inventory::find($id);
        if ($inventory) {
            $inventory->is_active = 0;
            $inventory->save();
        }

        return $inventory;
    }

    public function getInventoryLedgers(int $inventoryId)
    {
        $ledgers = (new GetInventoryStockAction($inventoryId))->run();

        return $ledgers;
    }
}
