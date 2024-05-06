<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PurchaseOrderItemLeft\PurchaseOrderItemLeftInterface;

class PurchaseOrderItemLeftController extends Controller
{
    //
    protected $purchaseOrderItemLeftRepo;
    public function __construct(PurchaseOrderItemLeftInterface $purchase_order_item_left_repo)
    {
        $this->purchaseOrderItemLeftRepo = $purchase_order_item_left_repo;
    }
    

    public function index(Request $request){
        $data=$this->purchaseOrderItemLeftRepo->list($request);
        ResponseData($data);
    }

    public function show($purchase_order_id){
        $data=$this->purchaseOrderItemLeftRepo->detail($purchase_order_id);
        ResponseData($data);
    }
}
