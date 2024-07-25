<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Repositories\FoodOrder\FoodOrderRepositoryInterface;
use Illuminate\Http\Request;

class FoodOrderAPIController extends Controller
{
    //
    protected $foodOrderRepo;

    public function __construct(FoodOrderRepositoryInterface $foodOrderRepo)
    {
        $this->foodOrderRepo = $foodOrderRepo;
    }

    public function createOrder(Request $request)
    {
        $this->foodOrderRepo->createFoodOrder($request->all());
    }

}
