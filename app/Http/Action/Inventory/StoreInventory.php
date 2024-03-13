<?php

namespace App\Http\Action\Inventory;

use App\Models\InventoryLedger;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Relations\Relation;

class StoreInventory
{
    public function inventoryAction($model)
    {
        $morphMapName=RelationMorphName($model);

        switch ($morphMapName) {
            case 'purchase_order':
                $this->storePurchaseOrderToInventory($model,$morphMapName,'in');
                break;
        }
    }

    public function storeToInventoryLedger($model,$morphMapName,$action){
        $inventory_id=$model->createdBy->department->inventory->id;
        return InventoryLedger::create([
            'date'=>now(),
            'ledgerable_id'=>$model->id,
            'ledgerable_type'=>$morphMapName,
            'inventory_id'=>$inventory_id,
            'action'=>$action,
        ]);
    }

    public function storeItemToInventory($inventoryLedger,$item){
        return $inventoryLedger->inventory_ledger_items()->create([
            'item_id'=>$item->item_id,
            'quantity'=>$item->quantity,
            'inventory_ledger_-id'=>$inventoryLedger->id,
        ]);
    }

    public function storePurchaseOrderToInventory($model,$morphMapName,$action){
        $purchaseOrderItem=PurchaseOrderItem::with(['item'])->where('purchase_order_id',$model->id)->get();
        // $purchaseOrderItemGroupedByCategory=PurchaseOrderItem::
        // ->where('purchase_order_id',$model->id)->get();
        $inventoryLedger=$this->storeToInventoryLedger($model,$morphMapName,$action);
        foreach($purchaseOrderItem as $po_item){
            $this->storeItemToInventory($inventoryLedger,$po_item);
        }
        return $inventoryLedger;
    }

}
