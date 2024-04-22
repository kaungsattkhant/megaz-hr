<?php

namespace App\Http\Controllers\API;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\PurchaseOrderCreateRequest;
use App\Http\Requests\PurchaseOrder\PurchaseOrderUpdateRequest;
use App\Repositories\PurchaseOrder\PurchaseOrderRepositoryInterface;

class PurchaseOrderAPIController extends Controller
{
    //
    protected $purchaseOrderRepo;
    public function __construct(PurchaseOrderRepositoryInterface $purchaseOrderRepo)
    {
        $this->purchaseOrderRepo = $purchaseOrderRepo;
    }

    public function getPurchaseOrder(Request $request)
    {
        $purchaseOrders = $this->purchaseOrderRepo->listAllData($request);
        ResponseData($purchaseOrders);
    }

    public function createPurchaseOrder(PurchaseOrderRequest $request)
    {
        $purchaseOrder= $this->purchaseOrderRepo->createOrUpdate($request);
        ResponseData($purchaseOrder);
    }

    public function updatePurchaseOrder(PurchaseOrderUpdateRequest $request,int $id)
    {
        if($request->condition)
        {
            $purchaseOrder = $this->purchaseOrderRepo->updateKitchenAndFinancePO($request->condition,$request->all(),$id);
            ResponseData($purchaseOrder);
        }else{
            $purchaseOrder = $this->purchaseOrderRepo->updateData($request->all(),$id);
            ResponseData($purchaseOrder);
        }

    }

    public function detail(PurchaseOrder $purchase_order){
        $purchaseOrder= $this->purchaseOrderRepo->detail($purchase_order);
        ResponseData($purchaseOrder);
    }

    public function deletePurchaseOrder(int $id)
    {
        $purchaseOrder = $this->purchaseOrderRepo->deleteData($id);
        if($purchaseOrder==true)
        {
            ResponseMessage('Purchase Order deleted');
        }else{
            ResponseMessage('Purchase Order not found or some error occur');
        }
    }

    public function deletePurchaseOrderItem($id){
        $purchase_order_item= $this->purchaseOrderRepo->deletePurchaseOrderItem($id);
        // ResponseMessage('Item delete successfully',200);
    }

    public function updateIsCheck(Request $request){
        $purchase_order_item= $this->purchaseOrderRepo->updateIsCheck($request);
    }

    public function boughtPurchaseOrder(Request $request){
        $purchase_order_item= $this->purchaseOrderRepo->boughtPurchaseOrder($request);
    }
}
