<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PoInvoiceResource extends JsonResource
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
            'invoice_no' => $this->invoice_no,
            'date_time' => $this->date_time,
            'total_invoice_quantity' => $this->total_invoice_quantity,
            'total_invoice_amount' => $this->total_invoice_amount,
            'sub_total' => $this->subtotal ?? null,
            // 'discount_value' => $this->discount_value ?? null,
            // 'paid_amount' => $this->paid_amount ?? null,
            'item_names' => $this->item_names,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'brands' => $this->brands,
            'account_id' => $this->account_id,
            'creditor_account_id' => $this->creditor_account_id,
            'is_complete' => $this->is_complete,
            'completed_at' => $this->completed_at,
            'arrival_items' => $this->arrivalItems->map(function ($arrivalItem) {
                return [
                    'id' => $arrivalItem->id,
                    'item_id' => $arrivalItem->item->id,
                    'item_name' => $arrivalItem->item->name,
                    'quantity' => $arrivalItem->quantity,
                    'amount' => $arrivalItem->amount,
                    'unit_price' => number_format($arrivalItem->unit_price, 3),
                    'uom_id' => $arrivalItem->uom->id,
                    'uom_name' => $arrivalItem->uom->name,
                    'uom_quantity' => $arrivalItem->uom_quantity,
                    'base_uom_id' => $arrivalItem->baseUom->id,
                    'base_uom_name' => $arrivalItem->baseUom->name,
                    'base_uom_quantity' => $arrivalItem->base_uom_quantity,
                    'po_invoice_id' => $arrivalItem->po_invoice_id,
                    'purchase_order_id' => $arrivalItem->purchase_order_id,
                    'uom_conversion_unit_id' => $arrivalItem->uom_conversion_unit_id,
                    'brand_id' => $arrivalItem->brand->id,
                    'brand_name' => $arrivalItem->brand->name,
                    'supplier_id' => $arrivalItem->supplier_id,
                    'supplier_name' =>  $arrivalItem->supplier->name,
                    // 'unit_price',
                    // 'po_invoice_id',
                    // 'item_id',
                    // 'supplier_id',
                    // 'purchase_order_id',
                    // 'created_by'
                ];
            })
        ];
    }
}
