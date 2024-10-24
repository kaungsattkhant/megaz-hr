<?php
namespace App\Traits;

use App\Models\Customer;
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

    public function getCustomerLevel($customerTotal,$returnData){
        $levels = CustomerLevelDiscount::all();
            $customerLevel = null;
            $customerTotal=0;
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
}