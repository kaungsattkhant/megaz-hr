<?php

namespace App\Repositories\Transfer;

use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Models\InventoryLedger;
use App\Http\Action\Inventory\InventoryLedger as InventoryLedgerAction;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Common\Conversion;
use App\Http\Action\Inventory\StoreInventory;
use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;

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
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $transfers = Transfer::with(['item','uom', 'created_by', 'confirmed_by', 'source_inventory', 'destination_inventory'])
            ->where('created_by', UserData()->id)
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(transfers.created_at)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date) {
                $q->whereDate('transfers.created_at', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date) {
                $q->whereBetween('transfers.created_at', [now(), $to_date]);
            })
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
            // $uom_conversion = (new Conversion($request->uom_id, $request->base_uom_id))->run();
            #check is enough transfer quantity
            $quantity=$data['type']=='base_uom' ? $data['uom_conversion'] * $request->quantity : $request->quantity;
            (new InventoryLedgerAction($request->source_inventory_id))->isEnoughQuantityByItem($data['item_id'], $quantity);
            $no = (new CommonPurchaseOrder())->getUniqueId($latest, 'transfer_id', $count);
            $transfer_id = "TRS" . '-' . str_pad($no, $count, "0", STR_PAD_LEFT) . '-' . now()->timestamp;
            $data['transfer_id'] = $transfer_id;
            $data['source_inventory_id'] = $request->source_inventory_id;
            $data['created_by'] = UserData()->id;
            $data['date'] = convertDateFormat(now());
            $data['quantity'] = $quantity;
            $data['transfer_quantity'] = $request->quantity;
            $data['uom_conversion_id'] = $data['conversion_uom_id'];
            $data['uom_id'] = $request->uom_id;
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
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $inventory_ids = InventoryIds();
        return Transfer::with(['item', 'uom','created_by', 'confirmed_by', 'source_inventory', 'destination_inventory'])
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(transfers.confirmed_by)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date) {
                $q->whereDate('transfers.confirmed_by', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date) {
                $q->whereBetween('transfers.confirmed_by', [now(), $to_date]);
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
            // if (checkDepartmentAndRoles('Inventory', ['Staff'])) {
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
                $sourceLedger=InventoryLedger::create([
                    'date'=>now(),
                    'ledgerable_id'=>$transfer->id,
                    'ledgerable_type'=>'transfer',
                    'inventory_id'=>$inventoryId,
                    'action'=>'out',
                    'batch_no'=>$transfer->batch_no,
                ]);
                // $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($transfer, 'transfer', 'out');
                (new StoreInventory($inventoryId))->storeItemToInventory($sourceLedger, $transfer);
                #in
                $inventoryId = $transfer->destination_inventory_id;
                $destinationLedger=InventoryLedger::create([
                    'date'=>now(),
                    'ledgerable_id'=>$transfer->id,
                    'ledgerable_type'=>'transfer',
                    'inventory_id'=>$inventoryId,
                    'action'=>'in',
                    'batch_no'=>$transfer->batch_no,
                ]);
                // $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($transfer, 'transfer', 'in');
                (new StoreInventory($inventoryId))->storeItemToInventory($destinationLedger, $transfer);
                #store inventory
                DB::commit();
                ResponseMessage('Update Successfully', 200);
            }
            ResponseMessage('Not Found', 404);
            // }
            ResponseMessage("Permission isn't access", 404);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function cancelTransferItem(Request $request)
    {
        DB::beginTransaction();
        try {
            $transfer = Transfer::find($request->id);
            if ($transfer) {
                $transfer->confirmed_at = now();
                $transfer->confirmed_by = UserData()->id;
                $transfer->status = 'cancelled';
                $transfer->save();
                DB::commit();
                ResponseMessage('Update Successfully', 200);
            }
            ResponseMessage('Not Found', 404);
            // }
            ResponseMessage("Permission isn't access", 404);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    #end
}
