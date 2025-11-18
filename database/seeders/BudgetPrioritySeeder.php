<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BudgetPriority;

class BudgetPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for($i=1; $i<=30; $i++){
            $label = "P-{$i}";
            try{
                BudgetPriority::create([
                    'label' => $label,
                    'priority' => $i
                ]);
            }catch(\Exception $e){
                print($e->getMessage());
            }
        }
    }
}
