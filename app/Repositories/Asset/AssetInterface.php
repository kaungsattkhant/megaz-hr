<?php

namespace App\Repositories\Asset;

interface AssetInterface
{
    public function createAssetItem($request);

    public function createAsset($request);

    public function getAssetItemByAccount($request);
}
