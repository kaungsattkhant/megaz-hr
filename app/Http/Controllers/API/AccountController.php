<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountRequest;
use App\Repositories\Account\AccountInterface;

class AccountController extends Controller
{
    //
    private AccountInterface $accountRepo;

    public function __construct(AccountInterface $account_repo)
    {
        $this->accountRepo = $account_repo;
    }
    public function index(Request $request)
    {
        $account = $this->accountRepo->list($request);
        ResponseData($account);
    }
    public function store(AccountRequest $request)
    {
        $account = $this->accountRepo->updateOrCreate($request);
        ResponseData($account);
    }

    public function show(Account $account){
        $account= $this->accountRepo->detail($account);
        ResponseData($account);
    }

    public function getSubAccountByHeadAccount($head_account_id){
        $account= $this->accountRepo->getSubAccountByHeadAccount($head_account_id);
        ResponseData($account);
    }

    public function getCashAccount(){
        $sub_account= $this->accountRepo->getCashAccount();
        ResponseData($sub_account);
    }

    public function accountBySubAccount($sub_account_id){
        $sub_account= $this->accountRepo->accountBySubAccount($sub_account_id);
        ResponseData($sub_account);
    }

    public function getSecondAccount($type){
        $data= $this->accountRepo->getSecondAccount($type);
        ResponseData($data);
    }

    public function getThirdAccount($type){
        $data= $this->accountRepo->getThirdAccount($type);
        ResponseData($data);
    }

    public function createSecondAccount(Request $request){
        $data= $this->accountRepo->createSecondAccount($request);
        ResponseData($data);
    }

    public function createThirdAccount(Request $request){
        $data= $this->accountRepo->createThirdAccount($request);
        ResponseData($data);
    }

    public function createPrePaidAccount(Request $request)
    {
        $data= $this->accountRepo->prepaidAccountCreate($request);
        ResponseData($data);
    }

}
