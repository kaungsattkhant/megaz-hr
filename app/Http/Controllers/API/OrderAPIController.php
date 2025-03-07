<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderCreateRequest;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderAPIController extends Controller
{
    //
    protected $orderRepo;
    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function addOrder(Request $request)
    {
        if (isset($request->menuArray)) {
            $orders = $this->orderRepo->createMultipleOrder($request->all());
        } else {
            $orders = $this->orderRepo->createOrder($request->all());
        }
        ResponseData($orders);
    }

    public function orderItemChangeStatus(Request $request)
    {
        $orderItems = $this->orderRepo->orderItemStatusChange($request->all());
    }

    public function getOrderItemList(Request $request)
    {
        $orderItems = $this->orderRepo->getOrderItemData($request);
        ResponseData($orderItems);
    }

    // pos
    public function getOrderItemForPOS()
    {
        $data = $this->orderRepo->getOrderItemByPos();
        ResponseData($data);
    }

    public function orderItemAreaConfirm(int $id, Request $request)
    {
        $this->orderRepo->orderItemAreaConfirm($id, $request);
    }

    public function orderByInvoiceId(int $invoiceId)
    {
        $this->orderRepo->orderByInvoiceId($invoiceId);
    }

    public function checkFocSupervision(Request $request)
    {
        if (!$request->phone_number || !$request->password) {
            ResponseMessage('Phone number and password must be present', 400);
        }
        $this->orderRepo->checkFocSupervision($request);
    }

    public function combineOrderItem(Request $request){
        $data=$this->orderRepo->combineOrderItem($request);
        ResponseData($data);
    }

    public function getOrderItemGroupList(Request $request){
        $data=$this->orderRepo->getOrderItemGroupList($request);
        ResponseData($data);
    }
}
