<?php

namespace App\Http\Controllers\API;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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

    public function createPurchaseOrder(Request $request)
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
}
