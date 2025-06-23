<?php

namespace Database\Seeders;

use App\Models\NrcTownship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NrcTownshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $townshipJson = file_get_contents(database_path('data/nrc_townships.json'));
        $townships = json_decode($townshipJson, true);

        foreach ($townships['data'] as $township) {
            NrcTownship::create($township);
        }
    }
}
