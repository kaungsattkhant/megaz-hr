<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\Account;
use App\Models\AssetItem;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\AssetDepreciationBalance;
use App\Http\Action\Inventory\StoreInventory;
use App\Http\Action\Depreciation\DepreciationBalance;
use App\Http\Action\Transaction\StoreTransactionLedger;
use PhpParser\Node\Stmt\TryCatch;

class AssetRepository implements AssetInterface
{

    public function listAssetItems(Request $request)
    {
        $asset_items = AssetItem::orderBy('created_at', 'desc')->paginate(config('common.list_count'));
        ResponseData($asset_items);
    }

    public function listAsset(Request $request)
    {
        $assets = Asset::orderBy('created_at', 'desc')->paginate(config('common.list_count'));
        ResponseData($assets);
    }

    public function createAssetItem(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $assetItem = AssetItem::create($data);
            DB::commit();
            return $assetItem;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createAsset(Request $request)
    {
        //    dd($request->all());
        $data = $request->all();
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $model = Asset::create($data);

            if ($model) {
                #asset inventory ledger
                $data['asset_id'] = $model->id;
                (new StoreInventory($request->inventory_id))->storeAssetToInventory($data, 'in');
                #end asset inventory ledger
                #transaction
                $morphMapName = RelationMorphName($model);
                $data['date'] = now();
                $data['description'] = $model->name;
                $data['created_by'] = UserData()->id;
                $data['transactionable_id'] = $model->id;
                $data['is_confirmed'] = 1;
                $data['transactionable_type'] = $morphMapName;
                $transaction = (new StoreTransactionLedger())->createTransaction($data);
                $debitLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $model->cost,
                    'transaction_id' => $transaction->id,
                    'account_id' => $model->third_account_id,
                    'action' => 'debit',
                ]);
                $creditLedger = (new StoreTransactionLedger())->storeLedger([
                    'date' => now(),
                    'value' => $model->cost,
                    'transaction_id' => $transaction->id,
                    'account_id' => $request->cash_account_id,
                    'action' => 'credit',
                ]);
                #end transaction
                #add depreciation for next month
                // $assetDepreciation = (new DepreciationBalance())->addDepreciationBalance($model->id);
                #end
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getAssetItemByAccount($request)
    {
        $account = Account::where('id', $request->third_account_id)->first();
        $second_account_id = $second_depreciation_account_id = null;
        if ($account) {
            $second_account_id = $account->account_id;
        }
        $deptAccount = Account::where('id', $request->third_depreciation_id)->first();
        if ($deptAccount) {
            $second_depreciation_account_id = $deptAccount->account_id;
        }
        // dd($second_account_id,$second_depreciation_account_id);
        if ($second_account_id != null && $second_depreciation_account_id != null) {
            return AssetItem::where('second_account_id', $second_account_id)
                ->where('second_depreciation_account_id', $second_depreciation_account_id)
                ->get();
        }
        ResponseMessage('Item is empty', 419);
    }

    public function addDepreciationBalance($request)
    {
        $year = $request->year;  // The year you want to filter by
        $month = $request->month;    // The month you want to filter by
        // $results = DB::table('asset_depreciation_balances')
        //     ->select('third_account_id', 'asset_item_id')
        //     ->selectRaw(
        //         'COALESCE(SUM(CASE WHEN month = ? AND year = ? THEN addition_year_cost ELSE 0 END), 0) AS addition_year_cost',
        //         [$month, $year]
        //     )
        //     ->selectRaw(
        //         'COALESCE(SUM(CASE WHEN (year < ?) OR (year = ? AND month < ?) THEN addition_year_cost ELSE 0 END), 0) AS original_cost',
        //         [$year, $year, $month]
        //     )
        //     ->selectRaw(
        //         'COALESCE(SUM(CASE WHEN (year < ?) OR (year = ? AND month = ?) THEN addition_year_cost ELSE 0 END), 0) AS previous_month_addition',
        //         [$year, $year, $month - 1]
        //     )
        //     ->groupBy('third_account_id', 'asset_item_id')
        //     ->get();
        // $formattedResults = $results->map(function ($item) {
        //     // Calculate totals
        //     $total = $item->original_cost + $item->addition_year_cost;
        //     // Monthly depreciation for the current month
        //     $current_month_depreciation = floor($total / 12);
        //     // Total depreciation for the previous month
        //     $previous_month_depreciation = (int)$item->previous_month_addition ;
        //     // Addition year depreciation for previous months
        //     $addition_year_depreciation = $item->original_cost/12 ;
        //     $total_depreciation = $addition_year_depreciation + $current_month_depreciation;
        //     // Book value
        //     $book_value = $total - $total_depreciation;

        //     return [
        //         'third_account_id' => $item->third_account_id,
        //         'asset_item_id' => $item->asset_item_id,
        //         'original_cost' => $item->original_cost,
        //         'addition_year_cost' => $item->addition_year_cost,
        //         'total' => $total,
        //         'current_month_depreciation' => $current_month_depreciation,
        //         'addition_year_depreciation' => $addition_year_depreciation,
        //         'total_depreciation' => $total_depreciation,
        //         'book_value' => $book_value,
        //     ];
        // });

        //original
        // $now = now();
        // $previousMonth = now()->subMonth()->format('n');
        // $currentMonth = now()->format('n');
        // $currentYear = now()->format('Y');
        //end original
        //test

        $date = convertDateFormat($request->date);
        $now = Carbon::parse($date);
        $previousMonth = Carbon::parse($date)->subMonth()->format('n');
        $previousYear = Carbon::parse($date)->subMonth()->format('Y');
        $currentYear = Carbon::parse($date)->format('Y');
        $currentMonth = Carbon::parse($date)->format('n');
        $assets = Asset::whereMonth('purchase_date', $now)
            ->select('asset_item_id', 'third_account_id', 'third_depreciation_account_id', DB::raw('COALESCE(SUM(cost),0) as total_asset_cost'))
            ->groupBy('asset_item_id', 'third_account_id', 'third_depreciation_account_id')
            ->get();
        $assetDepreciations = AssetDepreciationBalance::where('month', $previousMonth)
            ->where('year', $previousYear)
            ->get();
        // return $assets;
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
                    $current_month_depreciation =    round($total_cost / 12);
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
        // $results = DB::table('asset_depreciation_balances')
        //     ->select('third_account_id', 'asset_item_id')
        //     ->selectRaw(
        //         'COALESCE(SUM(CASE WHEN month = ? AND year = ? THEN addition_year_cost ELSE 0 END), 0) AS addition_year_cost',
        //         [$month, $year]
        //     )
        //     ->selectRaw(
        //         'COALESCE(SUM(CASE WHEN (year < ?) OR (year = ? AND month < ?) THEN addition_year_cost ELSE 0 END), 0) AS original_cost',
        //         [$year, $year, $month]
        //     )
        //     ->selectRaw(
        //         'COALESCE(SUM(CASE WHEN (year < ?) OR (year = ? AND month = ?) THEN addition_year_cost ELSE 0 END), 0) AS previous_month_addition',
        //         [$year, $year, $month - 1]
        //     )
        //     ->selectRaw(
        //         'COALESCE(SUM(CASE WHEN (year < ?) OR (year = ? AND month < ?) THEN addition_year_cost ELSE 0 END), 0) AS previous_month_depreciation',
        //         [$year, $year, $month - 1]
        //     )
        //     ->groupBy('third_account_id', 'asset_item_id')
        //     ->get();

        // return $results;

    }

    public function updateOrCreateDepreciationBalance($data)
    {
        $updateOrCreated = AssetDepreciationBalance::updateOrCreate(
            ['id' => $data['id']],
            $data,
        );
        return $updateOrCreated;
    }
    public function addDepreciation($request)
    {
        DB::beginTransaction();
        try {
            $previousMonth = Carbon::parse(now())->subMonth();
            $assets = Asset::whereMonth('created_at', $previousMonth)->get();
            foreach ($assets as $asset) {

                $asset = Asset::find($asset->id);
                // $date = convertDateFormat($request->date);
                $date = now();
                $currentMonth = Carbon::parse($date)->format('n');
                $year = Carbon::parse($date)->format('Y');

                // $prevAssetDepreciation = AssetDepreciationBalance::where('month', $previousMonth)
                //     ->where('year', $year)
                //     ->where('third_account_id', $asset->third_account_id)
                //     ->where('third_depreciation_account_id', $asset->third_depreciation_account_id)
                //     ->where('asset_item_id', $asset->asset_item_id)
                //     ->latest()
                //     ->first();
                // $original_cost = $prevAssetDepreciation ? $prevAssetDepreciation->original_cost : 0;
                // $additionYearCost = $prevAssetDepreciation ? $prevAssetDepreciation->addition_year_cost : $asset->cost;
                // $total_cost = $original_cost + $additionYearCost;
                // $currentMonthDepreciation = $total_cost / 12;
                // $additionYearDepreciation = $prevAssetDepreciation ? $prevAssetDepreciation->addition_year_cost : 0;
                // $bookValue = $total_cost - ($currentMonthDepreciation + $additionYearDepreciation);
                //    $prevAssetDepreciation = AssetDepreciationBalance::where('third_account_id', $asset->third_account_id)
                //     ->where('third_depreciation_account_id', $asset->third_depreciation_account_id)
                //     ->where('asset_item_id', $asset->asset_item_id)
                //     ->latest()
                //     ->first();

                $additionYearCost = $asset->cost;
                // $additionYearDepreciation = $prevAssetDepreciation ? $prevAssetDepreciation->addition_year_cost : 0;
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
            }
            return $assetDepreciationBalance;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getDepreciationBalance($request){
        $date=Carbon::parse($request->date);
        $month=Carbon::parse($request->date)->format('n');
        $year=Carbon::parse($request->date)->format('Y');
        // return [$month,$year];
        $depreciationBalance=AssetDepreciationBalance::orderBy('asset_depreciation_balances.id','desc')->where('month',$month)
        ->where('year',$year)
        ->join('accounts','asset_depreciation_balances.third_account_id','accounts.id')
        ->select('asset_depreciation_balances.*','accounts.name')
        ->get();
        ResponseData($depreciationBalance);
    }





}
