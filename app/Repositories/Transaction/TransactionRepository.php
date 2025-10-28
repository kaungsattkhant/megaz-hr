<?php

namespace App\Repositories\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;

class TransactionRepository implements TransactionInterface
{
    public function list($request){
        // $transactions=Transaction::with(['ledgers'])->get();
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $account_id=$request->account_id;
        $query = Transaction::with(['ledgers' => function ($query)use($account_id) {
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
        }])
        ->when($account_id,function($q)use($account_id){
            $q->whereHas('ledgers',function($qa)use($account_id){
                $qa->where('id',$account_id);
            });
        })
        ->when(isset($request->is_confirmed),function($q)use($request){
            $q->isConfirmed($request->is_confirmed);
        });
        // ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
        //     $q->whereBetween(DB::raw('DATE(transactions.date)'), [$from_date, $to_date]);
        // })
        // ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
        //     $q->whereDate('date', '>=', $from_date);
        // })
        // ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
        //     $q->whereBetween('date', [now(), $to_date]);
        // });
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
                $ledger1Id=null;
                $ledger2Id=null;
            }
            if(isset($request->cash_account_code)){
                $account=Account::getByAccountCode($request->cash_account_code);
                if(!$account){
                    ResponseMessage('Cash Account does not exist',419);
                }
                $data['cash_account_id']=$account->id;
            }
            $data['created_by']=UserData()->id;
            $transaction=(new StoreTransactionLedger())->createTransaction($data);
            $action=$request->action=='debit' ? 'credit' : 'debit';
            $request_action=$request->action;
            $ledger1_request = "{$request_action}_ledger_id";
            $ledger2_request = "{$action}_ledger_id";
            $ledger1Id=$request->$ledger1_request;
            $ledger2Id=$request->$ledger2_request;

            $ledger1=(new StoreTransactionLedger())->storeLedger([
                'id'=>$ledger1Id,
                'value' => $request->value,
                'transaction_id' => $transaction->id,
                'account_id' => $request->account_id,
                'action' => $request->action,
            ]);
            $ledger2=(new StoreTransactionLedger())->storeLedger([
                'id'=>$ledger2Id,
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
        $transaction->action=$transaction->initialLedger->action;
        $transaction->account_id=$transaction->initialLedger->account_id;
        $transaction->cash_account_id=$transaction->finalLedger->account_id;
        if($transaction->action == 'credit'){
            $transaction->credit_ledger_id=$transaction->account_id;
            $transaction->debit_ledger_id=$transaction->cash_account_id;
        }
        if($transaction->action == 'debit'){
            $transaction->debit_ledger_id=$transaction->account_id;
            $transaction->credit_ledger_id=$transaction->cash_account_id;
        }

        $transaction->value=$transaction->initialLedger->value;
        $transaction->ledgers=$transaction->ledgers;
        return $transaction;
    }

    public function delete($id){
        $transaction=Transaction::find($id);
        if($transaction){
            $transaction->delete();
            $transaction->ledgers()->delete();
            ResponseMessage("Delete is successfully",200);
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