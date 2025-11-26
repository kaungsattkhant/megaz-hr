<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeadAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $name=[['Assets','1-0000'],['Current Assets','2-0000'],['Equity','3-0000'],['Liabilities','4-0000'],['Income','5-0000'],['Cost of Sales','6-0000']];
        foreach($name as $n){
            \App\Models\HeadAccount::create([
                'name'=>$n[0],
                'account_code'=>$n[1],
            ]);
        }

        
    }
}
