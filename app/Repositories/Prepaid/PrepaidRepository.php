<?php

namespace App\Repositories\Prepaid;

use App\Models\Prepaid;
use App\Models\PrepaidBalance;
use App\Models\PrepaidPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrepaidRepository implements PrepaidRepositoryInterface
{
    public function createPrepaid(Request $request)
    {
        DB::beginTransaction();
        try {
            $currentYear = date('Y');
            $currentMonth = date('m');

            $data = $request->all();
            $data['date_time'] = "2024-07-13 07:30";
            $data['created_by'] = UserData()->id;
            $prepaid = Prepaid::create($data);

            $prepaidBalance = PrepaidBalance::create([
                'year' => $currentYear,
                'month' => $currentMonth,
                'opening_balance' => $data['prepaid_amount'],
                'cost' => 0,
                'monthly_cost' => 0,
                'closing_balance' => $data['prepaid_amount'],
                'prepaid_amount' => $data['prepaid_amount'],
                'prepaid_id' => $prepaid->id
            ]);

            DB::commit();
            ResponseData($prepaid);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function addingPrepaid(Request $request)
    {
        DB::beginTransaction();
        try {
            $prepaid = Prepaid::find($request->prepaid_id);
            $prepaidBalance = $prepaid->prepaidBalance;
            $prepaidBalance->prepaid_amount += $request->amount;
            $prepaidBalance->save();
            $prePaidPayment = PrepaidPayment::create([
                'date_time' => CurrentTime(),
                'amount' => $request->amount,
                'cash_account_id' => $request->cash_account_id,
                'prepaid_id' => $request->prepaid_id
            ]);

            DB::commit();
            ResponseData($prePaidPayment);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function prepaidBalanceList($request)
    {
        $currentYear = date('Y');
        $month = $request->input('month', date('m'));

        $prepaids = PrepaidBalance::where('month', $month)
            ->where('year', $currentYear)
            ->with('prepaid')
            ->paginate(config('common.list_count'));

        ResponseData($prepaids);
    }
}
