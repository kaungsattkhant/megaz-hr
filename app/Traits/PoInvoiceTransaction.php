<?php
namespace App\Traits;

use App\Models\Account;
use App\Models\Customer;
use App\Models\ArrivalItem;
use App\Models\AccountPayable;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerLevelDiscount;
use App\Http\Action\Transaction\StoreTransactionLedger;

trait PoInvoiceTransaction
{
    public function storeInvoiceTransaction($model, $amount,$cashAccountId,$supplierId)
    {
        $purchaseOrderItemGroupedByCategory = $this->groupedByCategoryAndSupplier($model);
        $data['date'] = now();
        $data['created_by'] = UserData()->id;
        $data['description'] = 'Po Invoice ';
        $data['transactionable_id'] = $model->id;
        $data['is_confirmed'] = 1;
        $data['transactionable_type'] = 'po_invoice';
        $transaction = (new StoreTransactionLedger())->createTransaction($data);
        foreach ($purchaseOrderItemGroupedByCategory as $po_category) {
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

        }
        if (!$cashAccountId) {
            ResponseMessage('Cash Account is Invalid', 419);
        }
        if ($cashAccountId) {
            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $amount,
                'transaction_id' => $transaction->id,
                'account_id' => $cashAccountId,
                'action' => 'credit',
            ]);
        }
        return $transaction;
        #credit
    }
    public function groupedByCategoryAndSupplier($model)
    {
        return ArrivalItem::join('po_invoices', 'arrival_items.po_invoice_id', 'po_invoices.id')
            // ->join('po_orders', 'arrival_items.purchase_order_id', '=', 'po_orders.purchase_order_id')
            ->join('items', 'arrival_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('suppliers', 'suppliers.id', '=', 'arrival_items.supplier_id')
            ->select(
                // 'suppliers.account_id',
                // 'suppliers.creditor_account_id',
                // 'suppliers.id as supplier_id',
                // 'suppliers.name as supplier_name', //don't need supplier
                'categories.name as category_name',
                'categories.id as category_id',
                DB::raw('SUM(arrival_items.amount) as total_amount'),
            )
            ->where('po_invoices.id', $model->id)
            ->groupBy('categories.id', 'categories.name')
            ->get();
    }

    public function storeAP($transaction,$ap_amount, $supplier_id, $supplier_account_id, $cash_account_id)
    {
        if ($ap_amount > 0) {
            $accountPayable = AccountPayable::create([
                'type' => 'addition',
                'date_time' => now(),
                'amount' => $ap_amount,
                'supplier_id' => $supplier_id,
                'account_id' => $supplier_account_id,
                'cash_account_id' => $cash_account_id,
                'created_by' => UserData()->id,
            ]);
            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $ap_amount,
                'transaction_id' => $transaction->id,
                'account_id' => $supplier_account_id,
                'action' => 'credit',
                'personable_id'=>$supplier_id,
                'personable_type'=>'supplier',
            ]);
        }
    }
}