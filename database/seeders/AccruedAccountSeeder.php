<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccruedAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseAccountCodes = Config::get('common.expense_account_codes', []);
        if (!empty($expenseAccountCodes)) {
            $expenseAccounts = Account::whereIn('account_code', $expenseAccountCodes)
                ->orderBy('account_code', 'asc')
                ->get();
        }

        $lastOtherPayable = Account::where('account_code', 'like', '4-4001')
        ->where('sub_account_id', 17) // Sub account ID for Other Payable
        ->first();
        $baseAccountCode = $lastOtherPayable->account_code;
        $counter = 1;
        $accruedAccounts = [];

        foreach ($expenseAccounts as $expenseAccount) {
                $accruedAccount = [
                    'account_code' => $baseAccountCode . "-" . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'name' => "Accured-{$expenseAccount->name}",
                    'sub_account_id' => $lastOtherPayable->sub_account_id,
                    'account_id' => $lastOtherPayable->id,
                    'link_account_id' => $expenseAccount->id,
                ];
                $accruedAccounts[] = $accruedAccount;
                $counter++;
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
