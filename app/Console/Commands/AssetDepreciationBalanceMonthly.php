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
        // $date = now();
        // $previousMonthDate = Carbon::parse($date)->subMonth();
        // $now = Carbon::parse($date);
        // $previousMonth = Carbon::parse($date)->subMonth()->format('n');
        // $previousYear = Carbon::parse($date)->subMonth()->format('Y');
        // $currentYear = Carbon::parse($date)->subMonth()->format('Y');
        // $currentMonth = Carbon::parse($date)->format('n');
        // $assetDepreciations = AssetDepreciationBalance::where('month', $previousMonth)
        //     ->where('year', $previousYear)
        //     ->join('assets', 'asset_depreciation_balances.asset_id', 'assets.id')
        //     ->select(
        //         'asset_depreciation_balances.*',
        //         'assets.useful_life',
        //         DB::raw('(SELECT COUNT(*) FROM asset_depreciation_balances adb WHERE adb.asset_id = assets.id) as depreciated_months')
        //     )
        //     ->get();
        // Log::info('Asset Depreciation: ', ['assets' => $assetDepreciations->toArray()]);
        // DB::beginTransaction();
        // try {
        //     foreach ($assetDepreciations as $depreciation) {
        //         if ($depreciation->depreciated_months >= $depreciation->useful_life) {
        //             Log::info("Asset {$depreciation->asset_id} fully depreciated. Skipping...");
        //             continue;
        //         }
        //         $original_cost = $depreciation->total_cost;
        //         $addition_year_cost = 0;  //for current month
        //         $total_cost = $original_cost + $addition_year_cost;
        //         $current_month_depreciation = round($total_cost / $depreciation->useful_life);
        //         $addition_year_depreciation = $depreciation->total_depreciation;
        //         $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
        //         $book_value = $total_cost - $total_depreciation;
        //         $depreciationData['asset_id'] = $depreciation->asset_id;
        //         $depreciationData['month'] = $currentMonth;
        //         $depreciationData['year'] = $currentYear;
        //         $depreciationData['date'] = $date;
        //         $depreciationData['original_cost'] = $original_cost;
        //         $depreciationData['addition_year_cost'] = $addition_year_cost;
        //         $depreciationData['total_cost'] = $total_cost;
        //         $depreciationData['current_month_depreciation'] = $current_month_depreciation;
        //         $depreciationData['addition_year_depreciation'] = $addition_year_depreciation;
        //         $depreciationData['total_depreciation'] = $total_depreciation;
        //         $depreciationData['book_value'] = $book_value;
        //         $createDepreciation = AssetDepreciationBalance::create($depreciationData);
        //         Log::info("Depreciation created for Asset {$depreciation->asset_id} for $currentMonth/$currentYear");
        //         // Log::info('Balance Reach reach');
        //     }

        //     Log::info('Successfully');
        //     DB::commit();
        //     Log::info('Db Commit Successfully');
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     ResponseMessage($e->getMessage(), 402);
        //     throw $e;
        // }
       $now = Carbon::now()->startOfMonth();

    // Get all active assets
    $assets = Asset::select('id', 'purchase_date', 'cost', 'useful_life')->get();

    DB::beginTransaction();
    try {
        foreach ($assets as $asset) {

            $purchaseDate = Carbon::parse($asset->purchase_date)->startOfMonth();

            // Total months passed since purchase
            $monthsPassed = $purchaseDate->diffInMonths($now) + 1; // include purchase month

            // Already depreciated months
            $depreciatedMonths = AssetDepreciationBalance::where('asset_id', $asset->id)->count();

            // Stop if fully depreciated
            if ($depreciatedMonths >= $asset->useful_life) {
                Log::info("Asset {$asset->id} fully depreciated. Skipping...");
                continue;
            }

            // Missing months to catch up
            $missingMonths = $monthsPassed - $depreciatedMonths;

            // Previous total depreciation
            $previousTotalDepreciation = AssetDepreciationBalance::where('asset_id', $asset->id)
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->value('total_depreciation') ?? 0;

            // Loop through missing months
            for ($i = 0; $i < $missingMonths; $i++) {

                $depreciationMonth = $purchaseDate->copy()->addMonths($depreciatedMonths + $i);

                // Stop if exceeding useful life
                if ($depreciatedMonths + $i >= $asset->useful_life) break;

                // Integer monthly depreciation
                if ($depreciatedMonths + $i == $asset->useful_life - 1) {
                    // Last month adjustment: take remaining amount
                    $currentMonthDep = $asset->cost - $previousTotalDepreciation;
                } else {
                    $currentMonthDep = intval($asset->cost / $asset->useful_life);
                }

                // Total depreciation so far
                $totalDep = $previousTotalDepreciation + $currentMonthDep;

                // Book value
                $bookValue = max(0, $asset->cost - $totalDep);

                // Create or update depreciation record
                $assetDepreciate=AssetDepreciationBalance::updateOrCreate(
                    [
                        'asset_id' => $asset->id,
                        'month'    => $depreciationMonth->format('n'),
                        'year'     => $depreciationMonth->format('Y'),
                    ],
                    [
                        'date'                        => $depreciationMonth,
                        'original_cost'               => $asset->cost,
                        'addition_year_cost'          => 0,
                        'total_cost'                  => $asset->cost,
                        'current_month_depreciation'  => $currentMonthDep,
                        'addition_year_depreciation'  => $previousTotalDepreciation,
                        'total_depreciation'          => $totalDep,
                        'book_value'                  => $bookValue,
                    ]
                );


                Log::info("Depreciation created for Asset {$asset->name} → {$depreciationMonth->format('M-Y')}");

                // Carry forward total depreciation
                $previousTotalDepreciation = $totalDep;
            }
        }

        DB::commit();
        Log::info('Depreciation schedule committed successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Depreciation schedule failed: '.$e->getMessage());
        throw $e;
    }
    }
    // $assets = Asset::whereMonth('purchase_date', $previousMonthDate)
    //     ->select('id', 'asset_item_id', 'third_account_id', 'third_depreciation_account_id', 'cost', 'useful_life')
    //     ->get();
    // Log::info('Asset Data: ', ['assets' => $assets->toArray()]);
    // foreach ($assets as $asset) {
    //     $data['asset_id'] = $asset->id;
    //     $data['month'] = $currentMonth;
    //     $data['year'] = $currentYear;
    //     $original_cost = 0;
    //     $addition_year_cost = $asset->cost;
    //     $total_cost = $original_cost + $addition_year_cost;   //current_depreciation
    //     $current_month_depreciation = round($total_cost / $asset->useful_life);
    //     $addition_year_depreciation = 0;
    //     $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
    //     $book_value = $total_cost - $total_depreciation;
    //     $data['id'] = null;
    //     $data['date'] = $date;
    //     $data['original_cost'] = $original_cost;
    //     $data['addition_year_cost'] = $addition_year_cost;
    //     $data['total_cost'] = $total_cost;
    //     $data['current_month_depreciation'] = $current_month_depreciation;
    //     $data['addition_year_depreciation'] = $addition_year_depreciation;
    //     $data['total_depreciation'] = $total_depreciation;
    //     $data['book_value'] = $book_value;
    //     $result = $this->updateOrCreateDepreciationBalance($data);
    //     Log::info('New Asset  reach');
    // }
    public function updateOrCreateDepreciationBalance($data)
    {
        $updateOrCreated = AssetDepreciationBalance::updateOrCreate(
            ['asset_id' => $data['asset_id'], 'year' => $data['year'], 'month' => $data['month']],
            $data,
        );
        return $updateOrCreated;
    }
}
