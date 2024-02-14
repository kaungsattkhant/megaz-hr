<?php

namespace App\Repositories\PurchaseOrderItem;

use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderItemRepository implements PurchaseOrderItemRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allPurchaseOrderItems = PurchaseOrderItem::all();
        $purchaseOrderItems = Pagination($allPurchaseOrderItems,$request,'purchaseOrderItems');
        return $purchaseOrderItems;
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
