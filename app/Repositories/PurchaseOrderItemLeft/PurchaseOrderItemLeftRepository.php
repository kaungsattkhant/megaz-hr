<?php

namespace App\Repositories\PurchaseOrderItemLeft;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItemLeft;
use App\Repositories\PurchaseOrderItemLeft\PurchaseOrderItemLeftInterface;

class PurchaseOrderItemLeftRepository implements PurchaseOrderItemLeftInterface
{

    public function list($request)
    {
        // return PurchaseOrder::with(['items.purchaseOrderItemLefts'])->orderBy('id','desc')
        // ->whereHas('items.purchaseOrderItemLeft',function($query){
        //     $query->whereNotNull('id');
        // })
        // ->get();
        return PurchaseOrderItemLeft::orderBy('id', 'desc')
            ->join('purchase_order_items', 'purchase_order_item_lefts.purchase_order_item_id', '=', 'purchase_order_items.id')
            ->join('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->select('purchase_orders.*')
            ->groupBy('purchase_orders.id')
            // ->where('purchase_orders.is_bought',1)
            ->paginate(config('common.list_count'));
    }
    

    public function detail($purchase_order_id)
    {
        $po = PurchaseOrder::with(['items' => function ($query) {
            $query->whereHas('purchaseOrderItemLeft');
        }])
            ->whereId($purchase_order_id)
            ->first();
        if($po){
            $po->items->load('purchaseOrderItemLeft');
            return $po;
        }
        ResponseMessage('Not Found',404);
    }
}
