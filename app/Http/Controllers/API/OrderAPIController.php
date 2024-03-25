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
        if($request->menuArray)
        {
            $orders = $this->orderRepo->createMultipleOrder($request->all());
        }else{
            $orders = $this->orderRepo->createOrder($request->all());

        }
        ResponseData($orders);
    }
}
