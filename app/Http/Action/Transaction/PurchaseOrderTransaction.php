<?php

namespace App\Http\Action\Transaction;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\AccountPayable;
use App\Models\Ledger;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;

class PurchaseOrderTransaction
{
    public function createTransaction($model, $transactionable_type = null, $cash_account_id)
    {
        $purchaseOrderItemGroupedByCategory=$this->groupedByCategoryAndSupplier($model);
       
        $purchaseOrderItemGroupedBySupplier=$this->groupedBySupplier($model);
       
        // dd($purchaseOrderItemGroupedByCategory);
        $data['date'] = now();
        $data['created_by'] = UserData()->id;
        $data['transactionable_id'] = $model->id;
        $data['is_confirmed'] = 1;
        $data['transactionable_type'] = $transactionable_type;

        foreach ($purchaseOrderItemGroupedByCategory as $po_category) {
            $transaction = (new StoreTransactionLedger())->createTransaction($data);
            $category_id = $po_category->category_id;
            $account_code = null;
            switch ($category_id) {
                case "1":
                    $account_code = '2-1021'; #Inventory Food
                    break;
                case "2":
                    $account_code = '2-1023'; #Inventory Tobacco
                    break;
                case "3":
                    $account_code = '2-1024'; #Inventory General
                    break;
                case "4":
                    $account_code = '2-1022'; #Inventory Beverage
                    break;
                case "5":
                    $account_code = '2-1025'; #Inventory Stationery
                    break;
            }
            if ($account_code == null) {
                ResponseMessage('Transaction fail', 419);
            }
            #debit
            if ($account_code) {
                $debitAccount = (new Account())->accountByCode($account_code); #Inventory Food
                if ($debitAccount) {
                    $debitLedger = (new StoreTransactionLedger())->storeLedger([
                        'value' => $po_category->total_amount,
                        'transaction_id' => $transaction->id,
                        'account_id' => $debitAccount->id,
                        'action' => 'debit',
                    ]);
                } else {
                    ResponseMessage('Account is Invalid', 419);
                }
            }
            #credit
            if ($cash_account_id) {
                $creditLedger = (new StoreTransactionLedger())->storeLedger([
                    'date' => now(),
                    'value' => $po_category->total_invoice_amount,
                    'transaction_id' => $transaction->id,
                    'account_id' => $cash_account_id,
                    'action' => 'credit',
                ]);
            } else {
                ResponseMessage('Account is Invalid', 419);
            }
            #store AP 
            if ($po_category->total_invoice_amount < $po_category->total_amount) {
                // dd($po_category->total_amount - $po_category->total_invoice_amount);
                $AP = (new StoreTransactionLedger())->storeLedger([
                    'date' => now(),
                    'value' => $po_category->total_amount - $po_category->total_invoice_amount,
                    'transaction_id' => $transaction->id,
                    'account_id' => $po_category->account_id,
                    'personable_id' => $po_category->supplier_id,
                    'personable_type' => 'supplier',
                    'action' => 'credit',
                ]);
            }
        }
        $this->storeAP($purchaseOrderItemGroupedBySupplier,$cash_account_id);
        return $purchaseOrderItemGroupedByCategory;
    }

    public function groupedByCategoryAndSupplier($model){
        return PurchaseOrderItem::join('po_grns', 'purchase_order_items.id', '=', 'po_grns.purchase_order_item_id')
        ->join('items', 'purchase_order_items.item_id', '=', 'items.id')
        ->join('categories', 'items.category_id', '=', 'categories.id')
        ->join('suppliers', 'suppliers.id', '=', 'po_grns.supplier_id')
        ->select(
            'suppliers.account_id',
            'suppliers.id as supplier_id',
            'suppliers.name as supplier_name',
            'categories.name as category_name',
            'items.category_id',
            DB::raw('SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount'),
            DB::raw('SUM(po_grns.invoice_amount) as total_invoice_amount')
        )
        ->where('purchase_order_items.purchase_order_id', $model->id)
        ->groupBy('suppliers.id', 'suppliers.name', 'categories.id', 'categories.name')
        ->get();
    }
    public function groupedBySupplier($model){
        return PurchaseOrderItem::join('po_grns', 'purchase_order_items.id', '=', 'po_grns.purchase_order_item_id')
        ->join('items', 'purchase_order_items.item_id', '=', 'items.id')
        ->join('categories', 'items.category_id', '=', 'categories.id')
        ->join('suppliers', 'suppliers.id', '=', 'po_grns.supplier_id')
        ->select(
            'suppliers.account_id',
            'suppliers.id as supplier_id',
            'suppliers.name as supplier_name',
            DB::raw('SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount'),
            DB::raw('SUM(po_grns.invoice_amount) as total_invoice_amount')
        )
        ->where('purchase_order_items.purchase_order_id', $model->id)
        ->groupBy('suppliers.id', 'suppliers.account_id', 'suppliers.name')
        ->get();
    }

    public function storeAP($accountPayables,$cash_account_id){
        foreach($accountPayables as $accountPayable){
            if ($accountPayable->total_invoice_amount < $accountPayable->total_amount) {
                $accountPayable=AccountPayable::create([
                    'type'=>'addition',
                    'date_time'=>now(),
                    'amount'=>$accountPayable->total_amount - $accountPayable->total_invoice_amount,
                    'supplier_id'=>$accountPayable->supplier_id,
                    'account_id'=>$accountPayable->account_id,
                    'cash_account_id'=>$cash_account_id,
                    'created_by'=>UserData()->id,
                ]);
            }
        }
    }
    public function storeLedger($data)
    {
        return Ledger::create($data);
    }
}
