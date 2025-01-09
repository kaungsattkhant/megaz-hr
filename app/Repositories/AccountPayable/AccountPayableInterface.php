<?php

namespace App\Repositories\AccountPayable;

interface AccountPayableInterface
{

    public function list($request);

    public function getPayableAccount();

    public function createPayableAccount($request);

    public function createPayableTransaction($request);

    public function listOfAccountPayableTransaction($request);

    public function getAccountPayableBalance($request);

    public function accountPayableReport($request);
}
