<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\PoOrder\PoOrderRepositoryInterface;
use Illuminate\Http\Request;

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



    // public function storePoOrderItems(Request $request)
    // {
    //     $data =  $this->PoOrderRepository->storePoOrderItems($request);
    //     ResponseData($data);
    // }
}
