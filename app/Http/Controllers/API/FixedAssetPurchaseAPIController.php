<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\FixedAssetPurchase\FixedAssetPurchaseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function createFixedAssetPurchaseData(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $fixedAssetPurchaseData = $this->fixRepo->createData($data);
        ResponseData($fixedAssetPurchaseData);
    }

    public function updateIsCheck(Request $request)
    {
        $fixedAssetPurchaseData = $this->fixRepo->updateIsCheck($request);
    }

    public function boughtFixedAsset(Request $request)
    {
        $fixedAssetPurchaseData = $this->fixRepo->fixedAssetBought($request);
    }

}
