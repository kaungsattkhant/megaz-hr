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
        try
        {
            $currentYear = date('Y');
            $currentMonth = date('m');

            $data = $request->all();
            $data['created_by'] = UserData()->id;
            $prepaid = Prepaid::create($data);

            $prepaidBalance = PrepaidBalance::create([
                'year' => $currentYear,
                'month' => $currentMonth,
                'opening_balance' => $data['prepaid_amount'],
                'cost' => 0,
                'monthly_cost' => $data['monthly_cost'],
                'closing_balance' => $data['prepaid_amount'],
                'prepaid_amount' => $data['prepaid_amount'],
                'payment' => 0,
                'prepaid_id' => $prepaid->id
            ]);

            $prepaidPayment = PrepaidPayment::create([
                'date_time' => CurrentTime(),
                'amount' => $data['prepaid_amount'],
                'cash_account_id' => $data['cash_account_id'],
                'prepaid_id' => $prepaid->id
            ]);
            DB::commit();
            ResponseData($prepaid);
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(),422);
            throw $e;
        }
    }

    public function createPaymentPrepaid(Request $request)
    {
        DB::beginTransaction();
        try{

            $prepaidPayment = PrepaidPayment::create([
                'date_time' => CurrentTime(),
                'amount' => $request->amount,
                'cash_account_id' => $request->cash_account_id
            ]);

        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(),422);
            throw $e;
        }
    }

    public function addPaymentPrepaid(Request $request)
    {
        DB::beginTransaction();
        try{

            $prepaidBalance = PrepaidBalance::where('prepaid_id')->first();

            $prepaidBalance->update([
                'opening_balance' => $prepaidBalance->opening_balance + $request->amount,
                ''
            ]);

            PrepaidPayment::create([
                'date_time' => CurrentTime(),
                'amount' => $request->amount,
                'cash_account_id' => $request->cash_account_id,
                'prepaid_id' => $request->prepaid_id
            ]);

        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(),422);
            throw $e;
        }
    }
}
