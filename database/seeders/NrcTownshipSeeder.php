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

        $townshipData = array_map(function ($t) {
            return [
                'id'         => $t['id'],
                'name_en'    => $t['name_en'],
                'name_mm'    => $t['name_mm'],
                'nrc_code'   => $t['nrc_code'],
                'created_at' => $t['created_at'],
                'updated_at' => $t['updated_at'],
            ];
        }, $townships['data']);
        NrcTownship::insert($townshipData);
    }
}
