<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(base_path('app/Console/Commands/data/accountlist.json'));
        $data = json_decode($json);
        DB::beginTransaction();
        try {
            foreach ($data as $obj) {
                $account = Account::create([
                    'account_code' => $obj->account_code,
                    'name' => $obj->name,
                    'sub_account_id' => $obj->sub_account_id,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
