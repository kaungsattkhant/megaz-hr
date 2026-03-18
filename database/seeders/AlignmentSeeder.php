<?php

namespace Database\Seeders;

use App\Models\Alignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names=['Strategy Issue', 'Structure Conflict', 'System Failure', 'Staff Gap', 'Skill Gap', 'Style Issue', 'Shared Value Breach'];
        foreach($names as $name){
            Alignment::firstOrCreate([
                'name'=>$name
            ],[
                'name' => $name
            ]);
        }
    }
}
