<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    public function store(Request $request)
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
}
