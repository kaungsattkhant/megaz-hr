<?php

namespace App\Repositories\FixedAssetPurchase;

use Illuminate\Http\Request;

interface FixedAssetPurchaseRepositoryInterface
{
    public function listAllData(Request $request);
}
