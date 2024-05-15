<?php

namespace App\Http\Action\Inventory;

use App\Http\Action\Transaction\PurchaseOrderTransaction;
use App\Models\InventoryLedger;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\Relation;

class StoreInventory
{
    private $inventoryId;

    public function __construct(int $inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }
    
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
            return InventoryLedger::create([
                'date'=>now(),
                'ledgerable_id'=>$model->id,
                'ledgerable_type'=>$morphMapName,
                'inventory_id'=>$this->inventoryId,
                'action'=>$action,
            ]);
    }

    public function storeItemToInventory($inventoryLedger,$item){
        return $inventoryLedger->inventory_ledger_items()->create([
            'item_id'=>$item->item_id,
            'quantity'=>$item->quantity*$item->conversionUom->conversion,
            'inventory_ledger_id'=>$inventoryLedger->id,
        ]);
    }

    public function storePurchaseOrderToInventory($model,$morphMapName,$action){
        $purchaseOrderItem=PurchaseOrderItem::with(['item'])->where('purchase_order_id',$model->id)->get();
        $inventoryLedger=$this->storeToInventoryLedger($model,$morphMapName,$action);
        foreach($purchaseOrderItem as $po_item){
            $this->storeItemToInventory($inventoryLedger,$po_item);
        }
        (new PurchaseOrderTransaction())->createTransaction($model,$morphMapName);  #create transaction
        return $inventoryLedger;
    }

    

}
