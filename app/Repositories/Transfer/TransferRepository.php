<?php

namespace App\Repositories\Transfer;

use App\Http\Action\Common\Conversion;
use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;
use App\Http\Action\Common\UomConversion;
use App\Http\Action\Inventory\InventoryLedger;
use App\Http\Action\Inventory\StoreInventory;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferRepository implements TransferRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Transfer::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $transfers = Transfer::skip($skip)->take($perPage)->with('role.department')->get();
            $paginationData = MakePaginationData($request, $totalCount, 'tasks');
            $paginationData['tasks'] = $transfers;
            return $paginationData;
        } else {
            $transfers = Transfer::all();

            return $transfers;
        }
    }

    public function createData(array $data)
    {
        $data['status'] = "pending";
        $data['date'] = CurrentTime();
        $data['created_by'] = 1;
        $transfer = Transfer::create($data);
        return $transfer;
    }

    public function updateData(array $data, int $id)
    {
        $transfer = Transfer::find($id);
        if ($transfer) {
            $transfer->update($data);
        }
        return $transfer;
    }

    public function deleteData(int $id)
    {
        $transfer = Transfer::find($id);
        if ($transfer) {
            $transfer->delete();
            return true;
        } else {
            return false;
        }
    }

    public function transferConfirm(int $id)
    {
        $transfer = Transfer::find($id);
        if ($transfer) {
            $data['confirmed_at'] = currentTime();
            $data['confirmed_by'] = $id;
            $data['status'] = "confirmed";
            $transfer->update($data);
        }
        return $transfer;
    }

    #api

    public function list($request)
    {
        $transfers = Transfer::with(['item', 'created_by', 'confirmed_by', 'source_inventory', 'destination_inventory'])
            ->where('created_by', UserData()->id)
            ->paginate(config('common.list_count'));
        return $transfers;
    }

    public function createOrUpdate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $latest = Transfer::orderBy('created_at', 'desc')->first();
            $count = 4;
            $uom_conversion=(new Conversion($request->uom_id,$request->base_uom_id))->run();
            #check is enough transfer quantity
            $quantity=$uom_conversion->conversion*$request->quantity;
            (new InventoryLedger($request->source_inventory_id))->isEnoughQuantityByItem($request->item_id,$quantity);

            $no = (new CommonPurchaseOrder())->getUniqueId($latest, 'transfer_id', $count);
            $transfer_id = "TRS" . '-' . str_pad($no, $count, "0", STR_PAD_LEFT) . '-' . now()->timestamp;
            $data['transfer_id'] = $transfer_id;
            $data['source_inventory_id'] = $request->source_inventory_id;
            $data['created_by'] = UserData()->id;
            $data['date'] = convertDateFormat(now());
            $data['uom_conversion_id']=$uom_conversion->id;
            $data['uom_id']=$request->uom_id;
            $transfer = Transfer::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $transfer;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function transferConfirmationList($request)
    {
        $inventory_ids = InventoryIds();
        return Transfer::with(['item', 'created_by', 'confirmed_by', 'source_inventory', 'destination_inventory'])
            ->when( $request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(checkDepartmentAndRoles('Inventory', ['Staff']), function ($q) use ($inventory_ids) {
                $q->whereIn('destination_inventory_id', $inventory_ids);
            })
            ->paginate(config('common.list_count'));
    }

    public function confirmTransferItem($request)
    {
        DB::beginTransaction();
        try {
            if (checkDepartmentAndRoles('Inventory', ['Staff'])) {
                $transfer = Transfer::find($request->id);
                if ($transfer) {
                    // if ($transfer->confirmed_at != null && $transfer->confirmed_by != null) {
                    //     ResponseMessage('Already checked', 200);
                    // }
                    $transfer->confirmed_at = now();
                    $transfer->confirmed_by = UserData()->id;
                    $transfer->status = 'complete';
                    $transfer->save();
                    #store inventory
                    
                    #out
                    $inventoryId = $transfer->source_inventory_id;
                    $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($transfer, 'transfer', 'out');
                    (new StoreInventory($inventoryId))->storeItemToInventory($inventoryLedger, $transfer);

                    #in
                    $inventoryId = $transfer->destination_inventory_id;
                    $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($transfer, 'transfer', 'in');
                    (new StoreInventory($inventoryId))->storeItemToInventory($inventoryLedger, $transfer);
                    #store inventory
                    DB::commit();
                    ResponseMessage('Update Successfully', 200);
                }
                ResponseMessage('Not Found', 404);
            }
            ResponseMessage("Permission isn't access", 404);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    #end
}
