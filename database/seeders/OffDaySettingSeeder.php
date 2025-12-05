<?php

namespace Database\Seeders;

use App\Models\OffDaySetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OffDaySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        OffDaySetting::create([
            'type'=>'default',
        ]);
        Log::info('OffDayCreate Successfully');
    }
}
