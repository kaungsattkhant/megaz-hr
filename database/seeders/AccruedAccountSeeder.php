<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccruedAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseAccounts = Account::where(function($query) {
                    $query->where('account_code', 'like', '6-2%')
                    ->orWhere('account_code', 'like', '6-3%')
                    ->orWhere('account_code', 'like', '6-4%')
                    ->orWhere('account_code', 'like', '6-5%')
                    ->orWhere('account_code', 'like', '6-6%')
                    ->orWhere('account_code', 'like', '6-7%')
                    ->orWhere('account_code', 'like', '6-8%')
                    ->orWhere('account_code', 'like', '6-9%');
        })->orderBy('account_code', 'asc')
        ->get();


        $lastOtherPayable = Account::where('account_code', 'like', '4-4%')
        ->where('sub_account_id', 17) // Sub account ID for Other Payable
        ->orderBy('account_code', 'desc')
        ->first();

        $lastCode = $lastOtherPayable ? intval(substr($lastOtherPayable->account_code, 2)) : 4035;
        $accruedCode = $lastCode + 1;
        $accruedAccounts = [];

        foreach ($expenseAccounts as $expenseAccount) {
                $accruedAccount = [
                    'account_code' => "4-{$accruedCode}",
                    'name' => "Accured-{$expenseAccount->name}",
                    'sub_account_id' => $expenseAccount->sub_account_id,
                    'link_account_id' => $expenseAccount->id,
                ];
                $accruedAccounts[] = $accruedAccount;
                $accruedCode++;
            }

        DB::beginTransaction();
        try {
            foreach ($accruedAccounts as $account) {
                Account::create($account);
            }
            DB::commit();
            // $this->command->info('Created ' . count($accruedAccounts) . ' accrued accounts');
            // foreach ($accruedAccounts as $account) {
            //     $this->command->line("  {$account['account_code']} - {$account['name']} {$account['sub_account_id']}");
            // }
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error("Failed to create accrued accounts: {$e->getMessage()}");
            throw $e;
        }
    }
}
