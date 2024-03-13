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
        $name=['Assets','Current Assets','Equity','Liabilities','Income','Cost of Sales'];
        foreach($name as $n){
            \App\Models\HeadAccount::create([
                'name'=>$n,
            ]);
        }

        
    }
}
