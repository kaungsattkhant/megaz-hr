<?php

namespace App\Repositories\PurchaseOrderItem;

use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderItemRepository implements PurchaseOrderItemRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = PurchaseOrderItem::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $purchaseOrderItems = PurchaseOrderItem::skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'purchase_order_items');
            $paginationData['purchaseOrderItems'] = $purchaseOrderItems;

            return $paginationData;
        }
        else{
            $purchaseOrderItems = PurchaseOrderItem::all();

            return $purchaseOrderItems;
        }
    }

    public function createData(array $data)
    {
        $purchaseOrderItem = PurchaseOrderItem::create($data);
        return $purchaseOrderItem;
    }

    public function updateData(array $data, int $id)
    {
        $purchaseOrderItem = PurchaseOrderItem::find($id);
        if($purchaseOrderItem)
        {
            $purchaseOrderItem->update($data);
        }
        return $purchaseOrderItem;
    }

    public function deleteData(int $id)
    {
        $purchaseOrderItem = PurchaseOrderItem::find($id);
        if($purchaseOrderItem)
        {
            $purchaseOrderItem->delete();
            return true;
        }else{
            return false;
        }
    }
}
