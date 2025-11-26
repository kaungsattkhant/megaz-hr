<?php

namespace App\Repositories\PurchaseOrderItemLeft;

interface PurchaseOrderItemLeftInterface
{
    public function list($request);

    public function detail($purchase_order_id);
 
}