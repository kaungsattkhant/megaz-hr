<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\FoodOrder\FoodOrderRepositoryInterface;
use Illuminate\Http\Request;

class FoodOrderAPIController extends Controller
{
    //
    protected $foodRepo;

    public function __construct(FoodOrderRepositoryInterface $foodRepo)
    {
        $this->foodRepo = $foodRepo;
    }

    public function listAllFoodOrder(Request $request)
    {
        $this->foodRepo->listAllData($request);
    }

    public function createFoodOrder(Request $request)
    {
        $this->foodRepo->createFoodOrder($request->all());
    }

    public function confirmFoodOrderItem(int $id,Request $request)
    {
        $this->foodRepo->confirmFoodOrderItem($id,$request);
    }

    public function confirmFoodOrder(int $id,Request $request)
    {
        $this->foodRepo->confirmFoodOrder($id,$request);
    }

    public function createConfirmFoodOrder(Request $request)
    {
        $this->foodRepo->createConfirmFoodOrder($request);
    }

    public function updateFoodTimeAndStatus(int $id, Request $request)
    {
        $this->foodRepo->updateFoodOrderStatus($id,$request);
    }

    public function foodOrderListForKitchen(Request $request)
    {
        $this->foodRepo->foodOrderListForKitchen($request);
    }

}
