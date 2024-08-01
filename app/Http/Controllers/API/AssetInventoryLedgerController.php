<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AssetInventoryLedger\AssetInventoryLedgerInterface;
use Illuminate\Http\Request;

class AssetInventoryLedgerController extends Controller
{
    //
    private $assetInventoryRepo;
    public function __construct(AssetInventoryLedgerInterface $repo)
    {
        $this->assetInventoryRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->assetInventoryRepo->list($request);
        ResponseData($data);
    }
}
