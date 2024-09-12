<?php

namespace App\Repositories\Asset;

use Illuminate\Http\Request;

interface AssetInterface
{
    public function listAssetItems(Request $request);

    public function listAsset(Request $request);

    public function createAssetItem(Request $request);

    public function createAsset(Request $request);

    public function getAssetItemByAccount(Request $request);

    public function addDepreciation($request);
    public function getDepreciationBalance($request);
    public function addDepreciationBalance($request);

}
