<?php

namespace App\Repositories\StaffAdvance;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\StaffAdvance;
use App\Models\StaffBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffAdvanceRepository implements StaffAdvanceRepositoryInterface
{

    public function createStaffAdvance(Request $reqeust)
    {
        DB::beginTransaction();
        try {
            $currentYear = date('Y');
            $currentMonth = date('m');
            $data = $reqeust->all();
            $data['date_time'] = CurrentTime();
            // $data['date_time'] = "2024-07-13 07:30";
            $data['created_by'] = UserData()->id;
            $staffAdvance = StaffAdvance::create($data);
            $transaction = (new StoreTransactionLedger())->createTransaction([
                'date' => now(),
                'created_by' => UserData()->id,
                'description' => "staff",
                'transactionable_id' => $staffAdvance->id,
                'transactionable_type' => 'staff_advance',
                'is_confirmed' => 1,
            ]);
            $advanceAccount = Account::where('account_code', '2-1053')->first();

            if ($data['type'] == 'addition') {
                $creditLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $data['cash_account_id'],
                    'action' => 'credit',
                    'is_cashier_confirmed' => 0
                ]);

                $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $advanceAccount->id,
                    'action' => 'debit',
                    'is_cashier_confirmed' => 0
                ]);
            } else {
                $creditLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $data['cash_account_id'],
                    'action' => 'debit',
                    'is_cashier_confirmed' => 0
                ]);

                $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $advanceAccount->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 0
                ]);
            }
            $totalAddition = StaffAdvance::where('staff_id', $data['staff_id'])
                            ->whereYear('date_time',$currentYear)
                            ->whereMonth('date_time',$currentMonth)
                            ->where('type', 'addition')
                            ->sum('amount') ?? 0;

            $totalSettlement = StaffAdvance::where('staff_id', $data['staff_id'])
                            ->whereYear('date_time',$currentYear)
                            ->whereMonth('date_time',$currentMonth)
                            ->where('type', 'settlement')
                            ->sum('amount') ?? 0;

            $staffBalance = StaffBalance::where('staff_id', $data['staff_id'])
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->first();


            if ($staffBalance != null) {
                if ($data['type'] == 'settlement') {
                    if ($staffBalance->closing_balance < $data['amount']) {
                        ResponseMessage('Invalid Settlement', 422);
                    }
                }
                $staffBalance->closing_balance = $staffBalance->opening_balance + ($totalAddition - $totalSettlement);
                $staffBalance->save();
            } else {
                $staffValidation = StaffBalance::where('staff_id',$data['staff_id'])->latest()->first();
                if($staffValidation)
                {
                    $currentYearMonth = $currentYear * 12 + $currentMonth; // e.g., 2024 * 12 + 1 = 24289 for January 2024
                    $validationYearMonth = $staffValidation->year * 12 + $staffValidation->month; // e.g., 2023 * 12 + 12 = 24288 for December 2023
                    if ($validationYearMonth < $currentYearMonth) {
                        StaffBalance::create(
                            [
                                'staff_id' => $data['staff_id'],
                                'year' => $currentYear,
                                'month' => $currentMonth,
                                'opening_balance' => $staffValidation->closing_balance,
                                'closing_balance' => $staffValidation->closing_balance + ($totalAddition - $totalSettlement)
                            ]
                        );
                    }else{
                        ResponseMessage('Invalid data',422);
                    }

                }else{
                    if ($data['type'] == 'settlement') {
                        ResponseMessage("Invalid Data", 422);
                    }
                    $addition = 0;
                    $settlement = 0;
                    if ($data['type'] == 'addition') {
                        $addition = $data['amount'];
                    } else {
                        $settlement = $data['amount'];
                    }

                    $closing_balance = $addition - $settlement;
                    $staffBalance = StaffBalance::create([
                        'staff_id' => $data['staff_id'],
                        'year' => $currentYear,
                        'month' => $currentMonth,
                        'opening_balance' => 0,
                        'closing_balance' => $closing_balance
                    ]);
                }
            }

            DB::commit();
            ResponseData($staffAdvance, 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
