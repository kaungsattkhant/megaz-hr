<?php

namespace App\Http\Controllers\API;

use App\Models\HeadAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\HeadAccount\HeadAccountInterface;

class HeadAccountController extends Controller
{
    //
    private HeadAccountInterface $headAccountRepo;

    public function __construct(HeadAccountInterface $head_account_repo)
    {
        $this->headAccountRepo = $head_account_repo;
    }
    public function index(Request $request)
    {
        $itemUsageForecast = $this->headAccountRepo->headAccountList($request);
        ResponseData($itemUsageForecast);
    }
    public function store(Request $request)
    {
        $itemUsageForecast = $this->headAccountRepo->updateOrCreateHeadAccount($request);
        ResponseData($itemUsageForecast);
    }

    public function show(HeadAccount $headAccount){
        $itemUsageForecast= $this->headAccountRepo->detailHeadAccount($headAccount);
        ResponseData($itemUsageForecast);
    }

}
