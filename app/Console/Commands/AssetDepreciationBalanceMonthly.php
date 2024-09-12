<?php

namespace App\Console\Commands;

use App\Models\Asset;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\AssetDepreciationBalance;

class AssetDepreciationBalanceMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:asset-depreciation-balance-monthly';

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
        $date = convertDateFormat(now());
        $now = Carbon::parse($date);
        $previousMonth = Carbon::parse($date)->subMonth()->format('n');
        $previousYear = Carbon::parse($date)->subMonth()->format('Y');
        $currentYear = Carbon::parse($date)->subMonth()->format('Y');
        $currentMonth = Carbon::parse($date)->format('n');
        $assets = Asset::whereMonth('purchase_date', $now)
            ->select('asset_item_id', 'third_account_id', 'third_depreciation_account_id', DB::raw('COALESCE(SUM(cost),0) as total_asset_cost'))
            ->groupBy('asset_item_id', 'third_account_id', 'third_depreciation_account_id')
            ->get();
        $assetDepreciations = AssetDepreciationBalance::where('month', $previousMonth)
            ->where('year', $previousYear)
            ->get();
        DB::beginTransaction();
        try {
            foreach ($assetDepreciations as $depreciation) {
                $original_cost = $depreciation->total_cost;
                $addition_year_cost = 0;  //for current month
                $total_cost = $original_cost + $addition_year_cost;
                $current_month_depreciation = round($total_cost / 12);
                $addition_year_depreciation = $depreciation->total_depreciation;
                $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
                $book_value = $total_cost - $total_depreciation;
                $depreciationData['asset_item_id'] = $depreciation->asset_item_id;
                $depreciationData['third_account_id'] = $depreciation->third_account_id;
                $depreciationData['third_depreciation_account_id'] = $depreciation->third_depreciation_account_id;
                $depreciationData['month'] = $currentMonth;
                $depreciationData['year'] = $currentYear;
                $depreciationData['date'] = $date;
                $depreciationData['original_cost'] = $original_cost;
                $depreciationData['addition_year_cost'] = $addition_year_cost;
                $depreciationData['total_cost'] = $total_cost;
                $depreciationData['current_month_depreciation'] = $current_month_depreciation;
                $depreciationData['addition_year_depreciation'] = $addition_year_depreciation;
                $depreciationData['total_depreciation'] = $total_depreciation;
                $depreciationData['book_value'] = $book_value;
                $createDepreciation = AssetDepreciationBalance::create($depreciationData);
            }
            foreach ($assets as $asset) {
                $currentAssetDepreciation = AssetDepreciationBalance::where('month', $currentMonth)
                    ->where('year', $currentYear)
                    ->where('third_account_id', $asset->third_account_id)
                    ->where('third_depreciation_account_id', $asset->third_depreciation_account_id)
                    ->where('asset_item_id', $asset->asset_item_id)
                    ->latest()
                    ->first();
                $data['asset_item_id'] = $asset->asset_item_id;
                $data['third_account_id'] = $asset->third_account_id;
                $data['third_depreciation_account_id'] = $asset->third_depreciation_account_id;
                $data['month'] = $currentMonth;
                $data['year'] = $currentYear;
                if ($currentAssetDepreciation) {
                    $original_cost = $currentAssetDepreciation->original_cost;
                    $addition_year_cost = $asset->total_asset_cost;
                    $total_cost = $original_cost + $addition_year_cost;
                    $current_month_depreciation = round($total_cost / 12);
                    $addition_year_depreciation = round($original_cost / 12);
                    $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
                    $book_value = $total_cost - $total_depreciation;
                    $data['id'] = $currentAssetDepreciation->id;
                    $data['date'] = now();
                    $data['original_cost'] = $original_cost;
                    $data['addition_year_cost'] = $addition_year_cost;
                    $data['total_cost'] = $total_cost;
                    $data['current_month_depreciation'] = $current_month_depreciation;
                    $data['addition_year_depreciation'] = $addition_year_depreciation;
                    $data['total_depreciation'] = $total_depreciation;
                    $data['book_value'] = $book_value;
                    $this->updateOrCreateDepreciationBalance($data);
                } else {
                    $original_cost = 0;
                    $addition_year_cost = $asset->total_asset_cost;
                    $total_cost = $original_cost + $addition_year_cost;   //current_depreciation
                    $current_month_depreciation = round($total_cost / 12);
                    $addition_year_depreciation = 0;
                    $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
                    $book_value = $total_cost - $total_depreciation;
                    $data['id'] = null;
                    $data['date'] = $date;
                    $data['original_cost'] = $original_cost;
                    $data['addition_year_cost'] = $addition_year_cost;
                    $data['total_cost'] = $total_cost;
                    $data['current_month_depreciation'] = $current_month_depreciation;
                    $data['addition_year_depreciation'] = $addition_year_depreciation;
                    $data['total_depreciation'] = $total_depreciation;
                    $data['book_value'] = $book_value;
                    $this->updateOrCreateDepreciationBalance($data);
                }
            }
            DB::commit();
            ResponseMessage('Depreciation Balance updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
