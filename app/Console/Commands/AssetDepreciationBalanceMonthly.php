<?php

namespace App\Console\Commands;

use App\Models\Asset;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // $date = convertDateFormat(now());
        Log::info('depreciation reach');
        // $date = convertDateFormat('2024-08-1');
        $date=now();
        $previousMonthDate=Carbon::parse($date)->subMonth();
        $now = Carbon::parse($date);
        $previousMonth = Carbon::parse($date)->subMonth()->format('n');
        $previousYear = Carbon::parse($date)->subMonth()->format('Y');
        $currentYear = Carbon::parse($date)->subMonth()->format('Y');
        $currentMonth = Carbon::parse($date)->format('n');
        $assets = Asset::whereMonth('purchase_date', $previousMonthDate)
            ->select('id', 'asset_item_id', 'third_account_id', 'third_depreciation_account_id', 'cost', 'useful_life')
            ->get();
        $assetDepreciations = AssetDepreciationBalance::where('month', $previousMonth)
            ->where('year', $previousYear)
            ->join('assets', 'asset_depreciation_balances.asset_id', 'assets.id')
            ->select('asset_depreciation_balances.*', 'assets.useful_life')
            ->get();
        Log::info('Asset Data: ', ['assets' => $assets->toArray()]);
        DB::beginTransaction();
        try {
            foreach ($assetDepreciations as $depreciation) {
                $original_cost = $depreciation->total_cost;
                $addition_year_cost = 0;  //for current month
                $total_cost = $original_cost + $addition_year_cost;
                $current_month_depreciation = round($total_cost / $depreciation->useful_life);
                $addition_year_depreciation = $depreciation->total_depreciation;
                $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
                $book_value = $total_cost - $total_depreciation;
                $depreciationData['asset_id'] = $depreciation->asset_id;
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
                Log::info('Balance Reach reach');
            }
            foreach ($assets as $asset) {
                $data['asset_id'] = $asset->id;
                $data['month'] = $currentMonth;
                $data['year'] = $currentYear;
                $original_cost = 0;
                $addition_year_cost = $asset->cost;
                $total_cost = $original_cost + $addition_year_cost;   //current_depreciation
                $current_month_depreciation = round($total_cost / $asset->useful_life);
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
                $result = $this->updateOrCreateDepreciationBalance($data);
                Log::info('New Asset  reach');
            }
            Log::info('Successfully');
            DB::commit();
            Log::info('Db Commit Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    public function updateOrCreateDepreciationBalance($data)
    {
        $updateOrCreated = AssetDepreciationBalance::updateOrCreate(
            ['id' => $data['id']],
            $data,
        );
        return $updateOrCreated;
    }
}
