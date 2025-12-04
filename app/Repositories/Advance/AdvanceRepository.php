<?php

namespace App\Repositories\Advance;

use Carbon\Carbon;
use App\Models\Advance;
use Illuminate\Http\Request;
use App\Models\AdvancePayment;
use Illuminate\Support\Facades\DB;

class AdvanceRepository implements AdvanceInterface
{

    public function list($data){
        $advances=Advance::orderBy('id','desc');
        if(isset($data['page'])){
            $perPage=$data['perPage'] ?? config('common.list_count');
            return $advances->paginate($perPage);
        }
        return $advances->get();
    }
    public function create($data)
    {
        DB::beginTransaction(); // start transaction

        try {
            $advance= Advance::create($data);
            DB::commit();
           return $advance;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function getAdvancePaymentDetailByAdvance($advanceId){
        $advancePayments=AdvancePayment::where('advance_id',$advanceId)->get();
        // if($advancePayments){
        //     \ResponseMessage('No one of advance payment of this advance',419);
        // }
        return $advancePayments;
    }

    public function createAdvancePayment($data){
        $advanceId=$data['advance_id'];
        $paidAmount=$data['paid_amount'];
        $advance = Advance::findOrFail($advanceId);

        // 1. Update remaining amount
        $remainingAmount = $advance->remaining_amount - $paidAmount;

        // 2. Reduce remaining months by 1
        $remainingMonths = $advance->remaining_months - 1;

        // Avoid division by zero
        if ($remainingMonths <= 0) {
            $newDeduction = 0;
        } else {
            // 3. Recalculate deduction amount
            $newDeduction = round($remainingAmount / $remainingMonths, 2);
        }

        // 4. Save payment
        AdvancePayment::create([
            'staff_advance_id' => $advance->id,
            'paid_amount'      => $paidAmount,
            'payment_month'    => Carbon::now()->format('Y-m-d'),
        ]);

        // 5. Update advance main record
        $advance->update([
            'remaining_amount'          => $remainingAmount,
            'remaining_months'          => $remainingMonths,
            'current_deduction_amount'  => $newDeduction,
        ]);

        return [
            'message' => 'Payment recorded successfully',
            'remaining_amount' => $remainingAmount,
            'remaining_months' => $remainingMonths,
            'next_month_deduction' => $newDeduction,
        ];
    }
}