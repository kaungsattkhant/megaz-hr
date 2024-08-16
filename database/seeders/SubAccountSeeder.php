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

        $subOfAssets = [
            ['Fixed Assets (Tangible)','1-1000'],
            ['Fixed Assets (Intangible)','1-1100'],
            ['Accumulated Depreciation','1-1200'],
            ['Accumulated Amortization','1-1300'],
            ['Provisional Replacement','1-1350'],
        ];
        foreach($subOfAssets as $subAsset){
            $asset=HeadAccount::where('name','Assets')->first();
            SubAccount::create([
                'name'=>$subAsset[0],
                'account_code'=>$subAsset[1],
                'head_account_id'=>$asset->id,
            ]);
        }
        $subOfCurrentAsset = [
            ['Cash & Bank','2-1000'],
            ['Schedule Of Inventory Held','2-1020'],
            ['Other Receivable','2-1050'],
            ['Receivable Debtor','2-2000'],
            ['Prepaid','2-3000'],
            ['Deposit Payment','2-4000'],
        ];
        foreach($subOfCurrentAsset as $subCurrentAsset){
            $currentAsset=HeadAccount::where('name','Current Assets')->first();
            SubAccount::create([
                'name'=>$subCurrentAsset[0],
                'account_code'=>$subCurrentAsset[1],
                'head_account_id'=>$currentAsset->id,
            ]);
        }


        $subOfEquity=[
           ['Capital','3-1000'],
            ['Drawing','3-2000']
        ];
        foreach($subOfEquity as $subEquity){
            $equity=HeadAccount::where('name','Equity')->first();
            SubAccount::create([
                'name'=>$subEquity[0],
                'account_code'=>$subEquity[1],
                'head_account_id'=>$equity->id,
            ]);
        }

        $subOfLiabilities=[
            ['Longterm Liabilities','4-1000'],
            ['Current Liabilities','4-2000'],
            ['Customer Deposit Received','4-3000'],
            ['Other Payable','4-4000'],
        ];
        foreach($subOfLiabilities as $subLiabilities){
            $liabilities=HeadAccount::where('name','Liabilities')->first();
            SubAccount::create([
                'name'=>$subLiabilities[0],
                'account_code'=>$subLiabilities[1],
                'head_account_id'=>$liabilities->id,
            ]);
        }

        $subOfIncome=[
            ['Cash Sales (RT)','5-0000'],
            ['Cash Sales (KTV)','5-0100'],
            ['Other Income','5-1000'],
        ];
        foreach($subOfIncome as $subIncome){
            $income=HeadAccount::where('name','Income')->first();
            SubAccount::create([
                'name'=>$subIncome[0],
                'account_code'=>$subIncome[1],
                'head_account_id'=>$income->id,
            ]);
        }

        $subOfCOS=[
            ['Cost of Sales','6-0000'],
            ['Selling Expenses','6-2000'],
            ['Operation Expense','6-3000'],
            ['Admin & General Expense','6-4000'],
            ['Pay & Related Expense','6-5000'],
            ['Gov.Affairs','6-6000'],
            ['Fixed Expense','6-7000'],
            ['Finance Cost','6-8000'],
            ['Year Tax','6-9000'],
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
                'name'=>$subCOS[0],
                'account_code'=>$subCOS[1],
                'head_account_id'=>$cos->id,
            ]);
        }
    }

}
