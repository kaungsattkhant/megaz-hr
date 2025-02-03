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
            'item_names' => $this->item_names,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'account_id' => $this->account_id,
            'is_complete' => $this->is_complete,
            'completed_at' => $this->completed_at,
            'arrival_items' => $this->arrivalItems->map(function ($arrivalItem) {
                return [
                    'id' => $arrivalItem->id,
                    'item_id' => $arrivalItem->item->id,
                    'item_name' => $arrivalItem->item->name,
                    'quantity' => $arrivalItem->quantity,
                    'amount' => $arrivalItem->amount,
                    'uom_id' => $arrivalItem->uom->id,
                    'uom_name' => $arrivalItem->uom->name,
                    'uom_quantity' => $arrivalItem->uom_quantity,
                    'base_uom_id' => $arrivalItem->baseUom->id,
                    'base_uom_name' => $arrivalItem->baseUom->name,
                    'base_uom_quantity' => $arrivalItem->base_uom_quantity,
                    'po_invoice_id' => $arrivalItem->po_invoice_id,
                    'po_order_id' => $arrivalItem->po_order_id,
                    'uom_conversion_unit_id' => $arrivalItem->uom_conversion_unit_id,
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
