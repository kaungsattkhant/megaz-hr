<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;

class TransactionRepository implements TransactionInterface
{
    public function list($request){
        // $transactions=Transaction::with(['ledgers'])->get();
        $query = Transaction::with(['ledgers' => function ($query) {
            $query->select(
                'ledgers.id',
                'ledgers.value',
                'ledgers.action',
                'ledgers.transaction_id',
                'ledgers.account_id',
                'accounts.name AS account_name',
                DB::raw("IF(action = 'debit', value, 0) AS debit_amount"),
                DB::raw("IF(action = 'credit', value, 0) AS credit_amount")
            )->leftJoin('accounts', 'ledgers.account_id', '=', 'accounts.id');
        }]);
        // ->isConfirmed(1);
        if($request->per_page || $request->page){
            $transactions=$query->paginate(config('common.list_count'));
        }else{
            $transactions=$query->get();
        }
        return $transactions;
    }

    public function updateOrCreate($request){
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $data['created_by']=UserData()->id;
            $transaction=(new StoreTransactionLedger())->createTransaction($data);
            $action=$request->action=='debit' ? 'credit' : 'debit';
            $ledger1=(new StoreTransactionLedger())->storeLedger([
                'value' => $request->value,
                'transaction_id' => $transaction->id,
                'account_id' => $request->account_id,
                'action' => $request->action,
            ]);
            $ledger2=(new StoreTransactionLedger())->storeLedger([
                'value' => $request->value,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'action' => $action,
            ]);
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($transaction){
        dd('jjjj');
    }

    public function delete($id){
        $transaction=Transaction::find($id);
        if($transaction){
            $transaction->delete();
            $transaction->ledgers()->delete();
            ResponseMessage("Delete is successfully",422);
        }
        ResponseMessage("Data isn't found",422);
    }

    public function transactionConfirmed($request){
        $ids=$request->ids;
        $transaction=Transaction::whereIn('id',$ids)
        ->update([
            'is_confirmed'=>$request->value,
        ]);
        $transaction ? ResponseMessage('Transaction confimed successfully',200) : ResponseMessage('Transaction confirmed Fail',422);
    }
}