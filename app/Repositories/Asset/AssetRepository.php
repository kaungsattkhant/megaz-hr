<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\AssetItem;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;


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
            $model=Asset::create($data);
            $morphMapName = RelationMorphName($model);
            #transaction
            $data['date'] = now();
            $data['description']=$model->name;
            $data['created_by'] = UserData()->id;
            $data['transactionable_id'] = $model->id;
            $data['is_confirmed'] = 1;
            $data['transactionable_type'] = $morphMapName;
            $transaction = (new StoreTransactionLedger())->createTransaction($data);
            $debitLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $model->cost,
                'transaction_id' => $transaction->id,
                'account_id' => $model->third_account_id,
                'action' => 'debit',
            ]);
            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'date' => now(),
                'value' => $model->cost,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'action' => 'credit',
            ]);
            #end transaction
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
