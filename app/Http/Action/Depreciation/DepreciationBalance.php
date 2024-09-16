<?php 
namespace App\Http\Action\Depreciation;

use App\Models\Asset;
use Illuminate\Support\Carbon;
use App\Models\AssetDepreciationBalance;

class DepreciationBalance {
    public function addDepreciationBalance($asset_id){
        $asset = Asset::find($asset_id);
        $date = convertDateFormat($asset->created_at);
        $date = $asset ? $date : now();
        $nextMonth = Carbon::parse($date)->addMonth()->format('n');
        $year = Carbon::parse($date)->format('Y');
        // $prevAssetDepreciation = AssetDepreciationBalance::where('month', $previousMonth)
        //     ->where('year', $year)
        //     ->first();

        $original_cost = 0;
        $additionYearCost =  $asset->cost;
        $total_cost = $original_cost + $additionYearCost;
        $currentMonthDepreciation = $total_cost / 12;
        $additionYearDepreciation =  0;
        $bookValue = $total_cost - ($currentMonthDepreciation + $additionYearDepreciation);
        $currentMonth = Carbon::parse($date)->addMonth()->format('n');
        $firstDayOfNextMonth = Carbon::parse($date)->addMonth()->startOfMonth()->format('Y-m-d');
        $assetDepreciationBalance = AssetDepreciationBalance::create([
            'month' => $currentMonth,
            'year' => $year,
            'date' => now(),
            'asset_id' => $asset->id,
            'asset_item_id' => $asset->asset_item_id,
            'third_account_id' => $asset->third_account_id,
            'third_depreciation_account_id' => $asset->third_depreciation_account_id,
            'addition_year_cost' => $additionYearCost,
            // 'book_value' => $bookValue,
        ]);
        return  $assetDepreciationBalance;
    }
}