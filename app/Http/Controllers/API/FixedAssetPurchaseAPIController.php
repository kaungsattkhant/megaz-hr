<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\FixedAssetPurchase\FixedAssetPurchaseRepositoryInterface;
use Illuminate\Http\Request;

class FixedAssetPurchaseAPIController extends Controller
{
    //
    protected $fixRepo;
    public function __construct(FixedAssetPurchaseRepositoryInterface $fixRepo)
    {
        $this->fixRepo = $fixRepo;
    }

    public function getFixedAssetPurchaseData(Request $request)
    {
        $fixedAssetPurchaseData = $this->fixRepo->listAllData($request);
        ResponseData($fixedAssetPurchaseData);
    }

    public function createFixedAssetPurchase(Request $request)
    {
        $fixedAssetPurchaseData =
    }

}
