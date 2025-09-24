<?php 
namespace App\Http\Action\Depreciation;

use App\Models\Asset;
use Illuminate\Support\Carbon;
use App\Models\AssetDepreciationBalance;

class DepreciationBalance {
    public function addDepreciationBalance($asset_id){
        $asset = Asset::find($asset_id);
        if(!$asset){
            ResponseMessage('Asset not found',419);
        }
        $date = convertDateFormat($asset->created_at);
        $date = $asset ? $date : now();
        $nextMonth = Carbon::parse($date)->addMonth()->format('n');
        $year = Carbon::parse($date)->format('Y');

        $original_cost = 0;
        $additionYearCost =  (int)$asset->cost;
        $total_cost = $original_cost + $additionYearCost;
        $currentMonthDepreciation = intval($total_cost / $asset->useful_life);
        $additionYearDepreciation =  0;
        $bookValue = $total_cost - ($currentMonthDepreciation + $additionYearDepreciation);
        $currentMonth = Carbon::now()->format('n');
        $firstDayOfNextMonth = Carbon::parse($date)->addMonth()->startOfMonth()->format('Y-m-d');
        $assetDepreciationBalance = AssetDepreciationBalance::create([
            'month' => $currentMonth,
            'year' => $year,
            'date' => now(),
            'asset_id' => $asset->id,
            'original_cost'=>$original_cost,
            'addition_year_cost' => $additionYearCost,
            'total_cost'=>$total_cost,
            'current_month_depreciation'=>$currentMonthDepreciation,
            'addition_year_depreciation'=>$additionYearDepreciation,
            'total_depreciation'=>$currentMonthDepreciation,
            'book_value' => $bookValue,
        ]);
        return  $assetDepreciationBalance;
    }
}