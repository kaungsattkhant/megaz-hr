<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PoOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [


            'id' => $this->id,
            'base_uom_id' => $this->base_uom_id,
            'base_uom_quantity' => $this->base_uom_quantity,
            'uom_id' => $this->uom_id,
            'uom_quantity' => $this->uom_quantity,
            'uom_conversion_unit_id' => $this->uom_conversion_unit_id,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'item_id' => $this->item_id,
            'brand_id' => $this->brand_id,
            'supplier_id' => $this->supplier_id,
            'purchase_order_id' => $this->purchase_order_id,
            'item_price_id' => $this->item_price_id,
            'created_by' => $this->created_by,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'purchase_order' => [
                'id' => $this->purchaseOrder->id,
                'po_id' => $this->purchaseOrder->po_id,
                'total_price' => $this->purchaseOrder->total_price,
                'date' => $this->purchaseOrder->date,
                // 'created_by' => $this->purchaseOrder->created_by,
                // 'is_bought' => $this->purchaseOrder->is_bought,
                // 'manager_check_id' => $this->purchaseOrder->manager_check_id,
                // 'financial_check_id' => $this->purchaseOrder->financial_check_id,
                // 'manager_check_time' => $this->purchaseOrder->manager_check_time,
                // 'financial_check_time' => $this->purchaseOrder->financial_check_time,
                'md_check_time' => $this->purchaseOrder->md_check_time,
                'is_md_checked' => $this->purchaseOrder->is_md_checked,
                'status' => $this->purchaseOrder->status,
            ],
            'uom_conversion' => [
                'id' => $this->uomConversion->id,
                'base_unit_id' => $this->uomConversion->base_unit_id,
                'conversion_unit_id' => $this->uomConversion->conversion_unit_id,
                'conversion' => $this->uomConversion->conversion,
                // 'is_show' => $this->uomConversion->is_show,
                // 'is_active' => $this->uomConversion->is_active,
            ],
            'uom' => [
                'id' => $this->uom->id,
                'name' => $this->uom->name,
            ],
            'base_uom' => [
                'id' => $this->baseUom->id,
                'name' => $this->baseUom->name,
            ],
            'item' => [
                'id' => $this->item->id,
                'name' => $this->item->name,
                'code' => $this->item->code,
                'category_id' => $this->item->category_id,
                'item_type_id' => $this->item->item_type_id,
                'base_uom_id' => $this->item->base_uom_id,
                'uom_id' => $this->item->uom_id,
                'is_active' => $this->item->is_active,
                'lead_time' => $this->item->lead_time,
                'minimum_holding_amount' => $this->item->minimum_holding_amount,
                'base_uom_name' => $this->item->base_uom_name,
                'item_uom' => $this->item->item_uom,
                'uom_conversion' => $this->item->uom_conversion,
                'average_price' => $this->item->average_price,
                'brands' => $this->item->brands,
            ],
            'brand' => [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
            ],
            'supplier' => [
                'id' => $this->supplier->id,
                // 'account_id' => $this->supplier->account_id,
                // 'creditor_account_id' => $this->supplier->creditor_account_id,
                'name' => $this->supplier->name,
                'shop_name' => $this->supplier->shop_name,
                // 'address' => $this->supplier->address,
                // 'email' => $this->supplier->email,
                // 'credit_limit' => $this->supplier->credit_limit,
                // 'lead_time' => $this->supplier->lead_time,
                // 'credit_terms' => $this->supplier->credit_terms,
            ],
            'item_price' => [
                'id' => $this->itemPrice->id,
                'price' => $this->itemPrice->price,
                'supplier_item_id' => $this->itemPrice->supplier_item_id,
                'base_uom_id' => $this->itemPrice->base_uom_id,
            ],
        ];
    }
}
