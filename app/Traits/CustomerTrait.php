<?php
namespace App\Traits;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerLevelDiscount;

trait CustomerTrait
{
    public function getCustomerTotal($customerId)
    {
        $customer = Customer::find($customerId);
        $customerTotal = 0;
        if ($customer->invoices) {
            foreach ($customer->invoices as $customerInvoice) {
                $customerTotal += $customerInvoice->total;
            }
        }
        return $customerTotal;
    }

    public function getCustomerLevel($customerTotal, $returnData)
    {
        $levels = CustomerLevelDiscount::all();
        $customerLevel = null;
        $customerTotal = 0;
        foreach ($levels as $level) {
            if ($customerTotal >= $level->amount) {
                $customerLevel = $level;
            } else {
                break;
            }
        }
        if ($customerLevel !== null) {
            $returnData['customer_level'] = $customerLevel->name;
            $returnData['customer_level_discount_value'] = $customerLevel->promotion_value;
        } else {
            $returnData['customer_level'] = 'no customer level';
        }
        return $returnData;
    }

    public function getCustomerDepositBalance($customerId)
    {
        // $depositBalances = DB::table('customer_deposits')
        //     ->select(
        //         DB::raw('SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) as total_deposit'),
        //         DB::raw('SUM(CASE WHEN type = "withdrawal" THEN amount ELSE 0 END) as total_withdrawal'),
        //         DB::raw('(SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) - SUM(CASE WHEN type = "withdrawal" THEN amount ELSE 0 END)) as balance')
        //     )
        //     ->where('account_id', $customerAccountId)
        //     ->first();
        $depositBalances = DB::table('customer_deposits')
            ->select(
                DB::raw('COALESCE(SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END), 0) as total_deposit'),
                DB::raw('COALESCE(SUM(CASE WHEN type = "withdrawal" THEN amount ELSE 0 END), 0) as total_withdrawal'),
                DB::raw('COALESCE(SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) - SUM(CASE WHEN type = "withdrawal" THEN amount ELSE 0 END), 0) as balance')
            )
            ->where('is_cashier_confirmed',1)
            ->where('customer_id', $customerId)
            ->first();
        return $depositBalances->balance;


    }
}