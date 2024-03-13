<?php

namespace App\Http\Controllers\API;

use App\Models\SubAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\HeadAccount\HeadAccountInterface;

class SubAccountController extends Controller
{
    //
    private HeadAccountInterface $headAccountRepo;

    public function __construct(HeadAccountInterface $head_account_repo)
    {
        $this->headAccountRepo = $head_account_repo;
    }
    public function index(Request $request)
    {
        $itemUsageForecast = $this->headAccountRepo->subAccountList($request);
        ResponseData($itemUsageForecast);
    }
    public function store(Request $request)
    {
        $itemUsageForecast = $this->headAccountRepo->updateOrCreateSubAccount($request);
        ResponseData($itemUsageForecast);
    }

    public function show(SubAccount $sub_account){
        $itemUsageForecast= $this->headAccountRepo->detailSubAccount($sub_account);
        ResponseData($itemUsageForecast);
    }
}
