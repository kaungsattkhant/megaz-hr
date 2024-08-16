<?php

namespace App\Repositories\Prepaid;

use Illuminate\Http\Request;

interface PrepaidRepositoryInterface
{
    public function createPrepaid(Request $request);

    public function addingPrepaid(Request $request);

    public function prepaidBalanceList($request);
}
