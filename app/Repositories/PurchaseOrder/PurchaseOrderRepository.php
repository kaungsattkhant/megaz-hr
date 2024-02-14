<?php

namespace App\Repositories\PurchaseOrder;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allPurchaseOrders = PurchaseOrder::all();
        $purchaseOrders = Pagination($allPurchaseOrders, $request, 'purchase orders');
        return $purchaseOrders;
    }

    public function createData(array $data)
    {
        $purchaseOrders = PurchaseOrder::create($data);
        return $purchaseOrders;
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

    // po mean purchase order
    public function updateKitchenAndFinancePO(string $condition, array $data ,int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if($purchaseOrder)
        {
            if($condition=='kitchen')
            {
                $purchaseOrder->kitchen_check_id = $data['kitchen_id'];
                $purchaseOrder->kitchen_check_time = CurrentTime();
                $purchaseOrder->save();
            }elseif($condition=='financial')
            {
                $purchaseOrder->financial_check_id = $data['financial_id'];
                $purchaseOrder->financial_check_time = CurrentTime();
                $purchaseOrder->save();
            }
        }
        return $purchaseOrder;
    }
}
