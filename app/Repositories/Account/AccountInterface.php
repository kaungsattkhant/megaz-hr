<?php

namespace App\Repositories\Account;

use Illuminate\Http\Request;

interface AccountInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($headAccount);

    public function getSubAccountByHeadAccount($head_account_id);

    public function getCashAccount($request);

    public function accountBySubAccount($sub_account_id);

    public function createSecondAccount($request);

    public function createThirdAccount($request);

    public function getDepreciationAccount($request);

    public function getSecondAccount($type);

    public function getThirdAccount($type);

    public function prepaidAccountCreate(Request $request);

    public function prepaidAccountList();

    public function getSubAccountForAr();
}
