<?php

namespace Database\Seeders;

use App\Models\HeadAccount;
use App\Models\SubAccount;
use Illuminate\Database\Seeder;

class SubAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $subOfAssets = ['Fixed Assets (Tangible)',
            'Fixed Assets (Intangible)',
            'Accumulated Depreciation',
            'Accumulated Amortization',
            'Provisional Replacement',
        ];
        foreach($subOfAssets as $subAsset){
            $asset=HeadAccount::where('name','Assets')->first();
            SubAccount::create([
                'name'=>$subAsset,
                'head_account_id'=>$asset->id,
            ]);
        }
        $subOfCurrentAsset = [
            'Cash & Bank',
            'Stock',
            'Inventory',
            'Other Receivable',
            'Account Receivable',
            'Prepaid',
            'Deposit Paid',
        ];
        foreach($subOfCurrentAsset as $subCurrentAsset){
            $currentAsset=HeadAccount::where('name','Current Assets')->first();
            SubAccount::create([
                'name'=>$subCurrentAsset,
                'head_account_id'=>$currentAsset->id,
            ]);
        }


        $subOfEquity=[
            'Share Capital',
            'Drawing'
        ];
        foreach($subOfEquity as $subEquity){
            $equity=HeadAccount::where('name','Equity')->first();
            SubAccount::create([
                'name'=>$subEquity,
                'head_account_id'=>$equity->id,
            ]);
        }

        $subOfLiabilities=[
            'Longterm Liabilities',
            'Current Liabilities',
            'Other Payable',
        ];
        foreach($subOfLiabilities as $subLiabilities){
            $liabilities=HeadAccount::where('name','Liabilities')->first();
            SubAccount::create([
                'name'=>$subLiabilities,
                'head_account_id'=>$liabilities->id,
            ]);
        }

        $subOfIncome=[
            'Cash Sales (RT)',
            'Cash Sales (KTV)',
            'Other Income',
        ];
        foreach($subOfIncome as $subIncome){
            $income=HeadAccount::where('name','Income')->first();
            SubAccount::create([
                'name'=>$subIncome,
                'head_account_id'=>$income->id,
            ]);
        }

        $subOfCOS=[
            'Selling Expenses',
            'Operation Expense',
            'Admin & General Expense',
            'Pay & Related Expense',
            'Gov.Affairs',
            'Fixed Expense',
            'Finance Cost',
            'Year Tax',
            'Cost of Sales',
            // 'COS - Food',
            // 'COS - Beverage',
            // 'COS - Banquet/Function',
            // 'COS - Fuel',
            // 'COS - Carriage Inwards',
            // 'Selling Expenses',
            // 'Operation Expense',
            // 'Admin & General',
            // 'Pay & Related Exps',
            // 'Gov.Affairs',
            // 'Fixed Expense',
            // 'Finance Cost',
            // 'Year Tax',

        ];

        foreach($subOfCOS as $subCOS){
            $cos=HeadAccount::where('name','Cost of Sales')->first();
            SubAccount::create([
                'name'=>$subCOS,
                'head_account_id'=>$cos->id,
            ]);
        }
    }

}
