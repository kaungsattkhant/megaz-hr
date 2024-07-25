<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\AssetItem;
use Illuminate\Support\Facades\DB;


class AssetRepository implements AssetInterface
{

    public function createAssetItem($request){
        $data=$request->all();

        DB::beginTransaction();
        try {
            $data['created_by']=UserData()->id;
            $assetItem=AssetItem::create($data);
            DB::commit();
            return $assetItem;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createAsset($request){
        $data=$request->all();
        DB::beginTransaction();
        try {
            $data['created_by']=UserData()->id;
            $assetItem=Asset::create($data);
            DB::commit();
            return $assetItem;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
