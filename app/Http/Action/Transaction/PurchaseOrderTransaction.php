<?php

namespace App\Http\Action\Transaction;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;

class PurchaseOrderTransaction
{
    public function createTransaction($model, $transactionable_type = null,$cash_account_id)
    {
        $purchaseOrderItemGroupedByCategory = PurchaseOrderItem::join('items', 'purchase_order_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('categories.name', 'items.category_id', DB::raw('SUM(purchase_order_items.quantity * purchase_order_items.amount) as total_amount'))
            ->groupBy('items.category_id')
            ->where('purchase_order_id', $model->id)
            ->get();
        $data['date'] = now();
        $data['created_by'] = UserData()->id;
        $data['transactionable_id'] = $model->id;
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
                    'value' => $po_category->total_amount,
                    'transaction_id' => $transaction->id,
                    'account_id' => $cash_account_id,
                    'action' => 'credit',
                ]);
            } else {
                ResponseMessage('Account is Invalid', 419);
            }
        }
        return $purchaseOrderItemGroupedByCategory;
    }

    public function storeLedger($data)
    {
        return Ledger::create($data);
    }
}
