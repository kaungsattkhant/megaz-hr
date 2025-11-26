<?php

namespace App\Repositories\HeadAccount;

use Illuminate\Http\Request;

interface HeadAccountInterface
{
    public function headAccountList($request);

    public function updateOrCreateHeadAccount($request);

    public function detailHeadAccount($headAccount);

    public function subAccountList($request);

    public function updateOrCreateSubAccount($request);

    public function detailSubAccount($headAccount);

}