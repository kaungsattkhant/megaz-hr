<?php

namespace App\Http\Action\Common;

class PurchaseOrder
{
    public function getUniqueId($latest,$count){
        // $no=(new CommonPurchaseOrder())->getUniqueId($latest,$count);
        if ($latest) {
            $po_id_array = explode('-', $latest->po_id);
            $latest_po_id = (int) $po_id_array[1];
            if (strlen($latest_po_id + 1) > 4 && strlen($latest_po_id) == 4) {
                $count = strlen($latest_po_id) + 1;
            } elseif (strlen($latest_po_id + 1) > 4 && strlen($latest_po_id + 1) >= 5) {
                $count = strlen($latest_po_id + 1);
            }
            $no = $po_id_array[1] + 1;
        } else {
            $no = 1;
        }
        return $no;
    }
}
