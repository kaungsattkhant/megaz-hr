<?php

namespace App\Console\Commands;

use App\Models\Prepaid;
use App\Models\PrepaidBalance;
use App\Models\PrepaidPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PrepaidMonthlySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prepaid-monthly-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $currentYear = date('Y');
        $currentMonth = date('m');
        $lastMonth = Carbon::now()->subMonth();
        $prePaidBalances = PrepaidBalance::where('month',$lastMonth->month)->where('year',$lastMonth->year)->where('closing_balance', '!=', 0)->get();
        foreach($prePaidBalances as $prePaidBalance)
        {
            $prePaid = Prepaid::find($prePaidBalance->prepaid_id);
            $prePaidPaymentValue = PrepaidPayment::whereMonth('date_time',$lastMonth->month)->whereYear('date_time',$lastMonth->year)->where('prepaid_id',$prePaid->id)->sum('amount') ?? 0 ;
            $newPrePaidBalances = PrepaidBalance::create([
                'prepaid_id' => $prePaidBalance->prepaid_id,
                'year' => $currentYear,
                'month' => $currentMonth,
                'opening_balance' => ($prePaidBalance->closing_balance + $prePaidPaymentValue),
                'closing_balance' => (($prePaidBalance->closing_balance + $prePaidPaymentValue) - $prePaid->monthly_cost),
                'prepaid_amount' => $prePaidBalance->prepaid_amount + $prePaidPaymentValue,
                'monthly_cost' => $prePaid->monthly_cost,
                'cost' => $prePaid->monthly_cost
            ]);
        }
    }
}
