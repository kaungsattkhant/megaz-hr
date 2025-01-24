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
            'total_invoice_amount' => $this->total_invoice_amount,
            // 'date_time' => $this->date_time,
            'item_names' => $this->item_names,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'arrival_items' => $this->arrivalItems->map(function ($arrivalItem) {
                return [
                    'id' => $arrivalItem->id,
                    'item_id' => $arrivalItem->poOrder->item->id,
                    'item_name' => $arrivalItem->poOrder->item->name,
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
                ];
            })
        ];
    }
}
