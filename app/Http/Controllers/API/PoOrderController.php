<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PoOrder\PoOrderItemRequest;
use App\Repositories\PoOrder\PoOrderRepositoryInterface;

class PoOrderController extends Controller
{
    private PoOrderRepositoryInterface $PoOrderRepository;

    public function __construct(PoOrderRepositoryInterface $PoOrderRepository)
    {
        $this->PoOrderRepository = $PoOrderRepository;
    }

    public function getPoOrderItems(Request $request)
    {
        $data =  $this->PoOrderRepository->getPoOrderItems($request);
        ResponseData($data);
    }

    public function storePoOrderItems(PoOrderItemRequest $request)
    {
        $validatedData = $request->validated();
        $data =  $this->PoOrderRepository->storePoOrderItems($validatedData);
        ResponseData($data);
    }

    public function getPoOrderArrivalList(Request $request)
    {
        $data =  $this->PoOrderRepository->getPoOrderArrivalList($request);
        ResponseData($data);
    }

    public function getPoOrderArrivalListByItemId($itemId)
    {
        $data =  $this->PoOrderRepository->getPoOrderArrivalListByItemId($itemId);
        ResponseData($data);
    }

    public function getInvoiceBySupplier($supplierId)
    {
        $data =  $this->PoOrderRepository->getInvoiceBySupplier($supplierId);
        ResponseData($data);
    }
}
