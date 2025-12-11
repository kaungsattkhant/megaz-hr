<?php

namespace App\Repositories\Advance;

use App\Http\Resources\Mobile\StaffAdvanceResource;
use Carbon\Carbon;
use App\Models\Advance;
use Illuminate\Http\Request;
use App\Models\AdvancePayment;
use Illuminate\Support\Facades\DB;

class AdvanceRepository implements AdvanceInterface
{

    public function list($data){
        $advances=Advance::with(['staff:id,name'])->orderBy('id','desc');
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
            $data['date_time']=now();
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
        $advancePayments=AdvancePayment::with(['advance'])->where('advance_id',$advanceId)->get();
        // if($advancePayments){
        //     \ResponseMessage('No one of advance payment of this advance',419);
        // }
        return $advancePayments;
    }

    public function createAdvancePayment($data){
        try {
            $advanceId = $data['advance_id'];
            $paidAmount = $data['paid_amount'];
            $advance = Advance::findOrFail($advanceId);
            if (!$advance) {
                \ResponseMessage('Data not found', 419);
            }

            if($advance->remaining_amount<$paidAmount){
                \ResponseMessage(
                    "Paid amount is invalid,must be less than {$advance->remaining_amount}",419);
            }
            $remainingAmount = $advance->remaining_amount - $paidAmount;
            $remainingMonths = $advance->remaining_months - 1;

            // Avoid division by zero
            if ($remainingMonths <= 0) {
                $newDeduction = 0;
            } else {
                // 3. Recalculate deduction amount
                $newDeduction = round($remainingAmount / $remainingMonths, 2);
            }

            // 4. Save payment
            $advancePayment = AdvancePayment::create([
                'advance_id' => $advance->id,
                'paid_amount'      => $paidAmount,
                'remaining_balance'=> $remainingAmount,
                'payment_month'    => Carbon::now()->format('Y-m-d'),
            ]);

            // 5. Update advance main record
            $advance->update([
                'remaining_amount'          => $remainingAmount,
                'remaining_months'          => $remainingMonths,
                'current_deduction_amount'  => $newDeduction,
            ]);
            DB::commit();
            return $advancePayment;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
        
    }

    public function getStaffAdvanceHistory($data){
        $staffId=\UserData()->id;
        $advances = Advance::with(['advance_payment','staff:id,name'])
        ->orderBy('id', 'desc')
        ->where('staff_id',$staffId)
        ->get();
        // return $advances;    
        return StaffAdvanceResource::collection($advances);
    }
}