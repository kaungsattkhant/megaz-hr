<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use App\Models\AssetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\Asset\AssetInterface;

class AssetController extends Controller
{
    //
    private $assetRepo;

    public function __construct(AssetInterface $repo)
    {
        $this->assetRepo = $repo;
    }

    public function getAssetItemByAccount(Request $request){
        $data=$this->assetRepo->getAssetItemByAccount($request);
        ResponseData($data);

    }
    public function createAssetItem(Request $request){
        $data=$this->assetRepo->createAssetItem($request);
        ResponseData($data);

    }

    public function createAsset(Request $request){
        $data=$this->assetRepo->createAsset($request);
        ResponseData($data);

    }

    public function getAsset(Request $request)
    {
        $this->assetRepo->listAsset($request);
    }

    public function getAssetItem(Request $request)
    {
        $this->assetRepo->listAssetItems($request);
    }

    public function getDepreciationBalance(Request $request){
        return $this->assetRepo->getDepreciationBalance($request);
    }

    public function addDepreciation(Request $request){
        return $this->assetRepo->addDepreciation($request);
    }

    public function addDepreciationBalance(Request $request){
        return $this->assetRepo->addDepreciationBalance($request);
    }
}
