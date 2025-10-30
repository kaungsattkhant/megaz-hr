<?php

namespace App\Repositories\Accrued;


interface AccruedRepositoryInterface
{
    public function getExpenseAccount($request);
    public function createAccrued($request);
    public function getAccrued($request);
    public function detailAccrued($accountId);
    public function getOtherPayable($request);
}
