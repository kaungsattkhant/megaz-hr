<?php

namespace App\Repositories\Asset;

use App\Http\Action\Inventory\StoreInventory;
use App\Models\Asset;
use App\Models\Account;
use App\Models\AssetItem;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;
use Illuminate\Http\Request;

class AssetRepository implements AssetInterface
{

    public function listAssetItems(Request $request)
    {
        $asset_items = AssetItem::orderBy('created_at','desc')->paginate(config('common.list_count'));
        ResponseData($asset_items);
    }

    public function listAsset(Request $request)
    {
        $assets = Asset::orderBy('created_at','desc')->paginate(config('common.list_count'));
        ResponseData($assets);
    }

    public function createAssetItem(Request $request){
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

    public function createAsset(Request $request){
    //    dd($request->all());
        $data=$request->all();
        DB::beginTransaction();
        try {
            $data['created_by']=UserData()->id;
            $model=Asset::create($data);
            if($model){
                #asset inventory ledger
                $data['asset_id']=$model->id;
                (new StoreInventory($request->inventory_id))->storeAssetToInventory($data,'in');
                #end asset inventory ledger

                #transaction
                $morphMapName = RelationMorphName($model);
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
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getAssetItemByAccount($request)
    {
        $account=Account::where('id',$request->third_account_id)->first();
        $second_account_id=$second_depreciation_account_id=null;
        if($account){
            $second_account_id=$account->account_id;
        }
        $deptAccount=Account::where('id',$request->third_depreciation_id)->first();
        if($deptAccount){
            $second_depreciation_account_id=$deptAccount->account_id;
        }
        // dd($second_account_id,$second_depreciation_account_id);
        if($second_account_id!=null && $second_depreciation_account_id!=null){
            return AssetItem::where('second_account_id',$second_account_id)
            ->where('second_depreciation_account_id',$second_depreciation_account_id)
            ->get();
        }
        ResponseMessage('Item is empty',419);
    }
}
