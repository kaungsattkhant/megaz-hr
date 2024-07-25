<?php

namespace App\Repositories\FoodOrder;

use Illuminate\Http\Request;

interface FoodOrderRepositoryInterface
{

    public function listAllData(Request $request);

    public function createFoodOrder(array $data);

    public function confirmFoodOrderItem(int $id, Request $request);
}
