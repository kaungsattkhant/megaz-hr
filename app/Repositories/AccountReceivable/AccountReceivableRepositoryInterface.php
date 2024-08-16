<?php

namespace App\Repositories\AccountReceivable;

use Illuminate\Http\Request;

interface AccountReceivableRepositoryInterface
{
    public function createAR(Request $request);

    public function paidAr(Request $request);

    // public function accountReceivableList(Request $request);

   public function accountReceivableListDetail(int $id);
}
