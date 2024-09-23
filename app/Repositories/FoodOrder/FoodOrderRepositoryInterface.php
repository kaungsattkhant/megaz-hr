<?php

namespace App\Repositories\FoodOrder;

use Illuminate\Http\Request;

interface FoodOrderRepositoryInterface
{

    public function listAllData(Request $request);

    public function foodOrderListForKitchen(Request $request);

    public function createFoodOrder(array $data);

    public function confirmFoodOrder(int $id,Request $request);

    public function confirmFoodOrderItem(int $id, Request $request);

    public function createConfirmFoodOrder(Request $request);

    public function updateFoodOrderStatus(int $id,Request $request);

}
