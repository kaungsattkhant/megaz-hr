<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions=['Purchase Order','Staff','Department','Role','Area','Item'];
        foreach($permissions as $permission){
            Permission::create([
                'name'=>$permission,
                'slug'=>Str::slug($permission),
            ]);
        }
    }
}
