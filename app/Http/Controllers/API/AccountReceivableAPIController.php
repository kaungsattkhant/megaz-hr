<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AccountReceivable\AccountReceivableRepositoryInterface;
use Illuminate\Http\Request;

class AccountReceivableAPIController extends Controller
{
    //
    protected $arRepo;
    public function __construct(AccountReceivableRepositoryInterface $arRepo)
    {
        $this->arRepo = $arRepo;
    }

    public function createAccountReceivable(Request $request)
    {
        $this->arRepo->createAR($request);
    }

    public function paidAccountReceivable(Request $request)
    {
        $this->arRepo->paidAr($request);
    }

    public function accountReceivableList(Request $request)
    {
        $this->arRepo->accountReceivableList($request);
    }

    public function accountReceivableDetail(int $id)
    {
        $this->arRepo->accountReceivableListDetail($id);
    }
}
