<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Enums\TagType;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExtraTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tags')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Tag::firstOrCreate(
            ['type' => TagType::Extra->value],
            ['name' => TagType::Extra->name]
        );
        Tag::firstOrCreate(
            ['type' => TagType::Equipment->value],
            ['name' => TagType::Equipment->name]
        );
    }
}
