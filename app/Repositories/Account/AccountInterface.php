<?php

namespace App\Repositories\Account;

use Illuminate\Http\Request;

interface AccountInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($headAccount);

    public function getSubAccountByHeadAccount($head_account_id);

    public function getCashAccount();

    public function accountBySubAccount($sub_account_id);

    public function getPayableAccount();

}