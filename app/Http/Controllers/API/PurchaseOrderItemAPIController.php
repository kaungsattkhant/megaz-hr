<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderItem\PurchaseOrderItemCreateRequest;
use App\Http\Requests\PurchaseOrderItem\PurchaseOrderItemUpdateRequest;
use App\Models\PurchaseOrderItem;
use App\Repositories\PurchaseOrderItem\PurchaseOrderItemRepositoryInterface;
use Illuminate\Http\Request;

class PurchaseOrderItemAPIController extends Controller
{
    //
    protected $purchaseOrderItemRepo;

    public function __construct(PurchaseOrderItemRepositoryInterface $purchaseOrderItemRepo)
    {
        $this->purchaseOrderItemRepo = $purchaseOrderItemRepo;
    }

    public function getPurchaseOrderItem(Request $request)
    {
        $purchaseOrderItems = $this->purchaseOrderItemRepo->listAllData($request);
        ResponseData($purchaseOrderItems);
    }

    public function createPurchaseOrderItem(PurchaseOrderItemCreateRequest $request)
    {
        $purchaseOrderItem = $this->purchaseOrderItemRepo->createData($request->all());
        ResponseData($purchaseOrderItem);
    }

    public function updatePurchaseOrderItem(PurchaseOrderItemUpdateRequest $request, int $id)
    {
        $purchaseOrderItem = $this->purchaseOrderItemRepo->updateData($request->all(),$id);
        ResponseData($purchaseOrderItem);
    }

    public function deletePurchaseOrderItem(int $id)
    {
        $purchaseOrderItem = $this->purchaseOrderItemRepo->deleteData($id);
        if($purchaseOrderItem==true)
        {
            ResponseMessage('Purchase Order Item deleted');
        }else{
            ResponseMessage('Purchase Order Item not found or some error occur');
        }
    }
}

