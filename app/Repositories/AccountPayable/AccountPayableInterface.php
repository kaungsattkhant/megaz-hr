<?php

namespace App\Repositories\AccountPayable;

interface AccountPayableInterface
{

    public function list($request);

    public function getPayableAccount();

    public function createPayableAccount($request);
}
