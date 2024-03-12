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
        $sub_account = $this->headAccountRepo->subAccountList($request);
        ResponseData($sub_account);
    }
    public function store(Request $request)
    {
        $sub_account = $this->headAccountRepo->updateOrCreateSubAccount($request);
        ResponseData($sub_account);
    }

    public function show(SubAccount $sub_account){
        $sub_account= $this->headAccountRepo->detailSubAccount($sub_account);
        ResponseData($sub_account);
    }
}
