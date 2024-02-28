<?php

namespace App\Repositories\PurchaseOrder;

use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{
    public function listAllData(Request $request)
    {
        // if($request->per_page || $request->page){
        //     $totalCount = PurchaseOrder::count();
        //     $pageNumber = 1;
        //     $perPage = 20;
        //     if($request->page){
        //         $pageNumber = $request->page;
        //     }
        //     if($request->per_page){
        //         $perPage = $request->per_page;
        //     }
        //     $skip = ($pageNumber - 1) * $perPage;
        //     $purchaseOrders = PurchaseOrder::with(['items'])
        //     ->skip($skip)
        //     ->take($perPage)->get();
        //     $paginationData = MakePaginationData($request, $totalCount, 'purchase_orders');
        //     $paginationData['purchaseOrders'] = $purchaseOrders;
        //     return $paginationData;
        // }
        // else{
        //     $purchaseOrders = PurchaseOrder::all();

        //     return $purchaseOrders;
        // }

        $purchaseOrders = PurchaseOrder::with(['items'])
            ->orderBy('id', 'desc')
            ->paginate(20);
            return $purchaseOrders;
    }
    public function createOrUpdate($request)
    {
        // $purchaseOrder = PurchaseOrder::create($purchaseOrder);
        // foreach ($purchaseOrderItems as $poItem) {
        //     $poItem['purchase_order_id'] = $purchaseOrder->id; // Assign purchase_order_id to each purchase order item
        //     $purchaseOrderItem = PurchaseOrderItem::create($poItem);
        // }
        // return $purchaseOrder;
        $data = $request->all();
        $items = json_decode($request->items);
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $latest = PurchaseOrder::orderBy('created_at', 'desc')->first();
            $count = 4;
            $no = (new CommonPurchaseOrder())->getUniqueId($latest, $count);
            $po_id = "PO" . '-' . str_pad($no, $count, "0", STR_PAD_LEFT) . '-' . now()->timestamp;
            $data['po_id'] = $po_id;
            $data['created_by'] = 1;
            $po = PurchaseOrder::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            foreach ($items as $item) {
                $poItem =  $po->items()->create([
                    'quantity' => $item->quantity,
                    'purchase_order_id' => $po->id,
                    'item_id' => $item->item_id,
                    'amount' => $item->amount,
                ]);
            }
            DB::commit();
            return $po;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }

    }

    public function updateData(array $data, int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if ($purchaseOrder) {
            $purchaseOrder->update($data);
        }
        return $purchaseOrder;
    }

    public function deleteData(int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if ($purchaseOrder) {
            $purchaseOrder->delete();
            return true;
        } else {
            return false;
        }
    }

    public function detail($purchaseOrder){
        $purchaseOrder->items=$purchaseOrder->items;
        return $purchaseOrder;
    }

    // po mean purchase order
    public function updateKitchenAndFinancePO(string $condition, array $data, int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if ($purchaseOrder) {
            if ($condition == 'kitchen') {
                $purchaseOrder->kitchen_check_id = $data['kitchen_id'];
                $purchaseOrder->kitchen_check_time = CurrentTime();
                $purchaseOrder->save();
            } elseif ($condition == 'financial') {
                $purchaseOrder->financial_check_id = $data['financial_id'];
                $purchaseOrder->financial_check_time = CurrentTime();
                $purchaseOrder->save();
            }
        }
        return $purchaseOrder;
    }
}
