<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\DeliveryCharge\DeliveryChargeRepositoryInterface;
use Illuminate\Http\Request;

class DeliveryChargeAPIController extends Controller
{
    //
    protected $deliRepo;
    public function __construct(DeliveryChargeRepositoryInterface $deliRepo)
    {
        $this->deliRepo = $deliRepo;
    }

    public function getDeliveryChargeData(Request $request)
    {
        $this->deliRepo->listAllData($request);
    }

    public function createDeliveryCharge(Request $request)
    {
        $this->deliRepo->createData($request);
    }

}
