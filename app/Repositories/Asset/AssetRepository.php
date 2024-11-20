<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\Account;
use App\Models\AssetItem;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AssetDepreciationBalance;
use App\Http\Action\Inventory\StoreInventory;
use App\Http\Action\Depreciation\DepreciationBalance;
use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Services\DepreciationService;

class AssetRepository implements AssetInterface
{

    protected $depreciationService;

    public function __construct(DepreciationService $depreciationService)
    {
        $this->depreciationService = $depreciationService;
    }
    public function listAssetItems(Request $request)
    {
        $asset_items = AssetItem::orderBy('created_at', 'desc')->when($request->has('search'), function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('item_code', 'LIKE', '%' . $request->search . '%');
        })->paginate(config('common.list_count'));
        ResponseData($asset_items);
    }

    public function listAsset(Request $request)
    {
        $asset_items = Asset::orderBy('created_at', 'desc')->when($request->has('search'), function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('cost', 'LIKE', '%' . $request->search . '%')
                ->orwhere('quantity', 'LIKE', '%' . $request->search . '%');
        })->paginate(config('common.list_count'));
        ResponseData($asset_items);
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

    // public function addDepreciationBalanceOld($request)
    // {
    //     $year = $request->year;  // The year you want to filter by
    //     $month = $request->month;    // The month you want to filter by
    //     $date = convertDateFormat($request->date);
    //     $now = Carbon::parse($date);
    //     $previousMonth = Carbon::parse($date)->subMonth()->format('n');
    //     $previousYear = Carbon::parse($date)->subMonth()->format('Y');
    //     $currentYear = Carbon::parse($date)->format('Y');
    //     $currentMonth = Carbon::parse($date)->format('n');
    //     $assets = Asset::whereMonth('purchase_date', $now)
    //         ->select('id', 'asset_item_id', 'third_account_id', 'third_depreciation_account_id', 'cost', 'useful_life')
    //         ->get();
    //     $assetDepreciations = AssetDepreciationBalance::where('month', $previousMonth)
    //         ->where('year', $previousYear)
    //         ->get();
    //     DB::beginTransaction();
    //     try {
    //         foreach ($assetDepreciations as $depreciation) {
    //             $original_cost = $depreciation->total_cost;
    //             $addition_year_cost = 0;  //for current month
    //             $total_cost = $original_cost + $addition_year_cost;
    //             $current_month_depreciation = round($total_cost / 12);
    //             $addition_year_depreciation = $depreciation->total_depreciation;
    //             $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
    //             $book_value = $total_cost - $total_depreciation;
    //             $depreciationData['asset_item_id'] = $depreciation->asset_item_id;
    //             $depreciationData['third_account_id'] = $depreciation->third_account_id;
    //             $depreciationData['third_depreciation_account_id'] = $depreciation->third_depreciation_account_id;
    //             $depreciationData['month'] = $currentMonth;
    //             $depreciationData['year'] = $currentYear;
    //             $depreciationData['date'] = $date;
    //             $depreciationData['original_cost'] = $original_cost;
    //             $depreciationData['addition_year_cost'] = $addition_year_cost;
    //             $depreciationData['total_cost'] = $total_cost;
    //             $depreciationData['current_month_depreciation'] = $current_month_depreciation;
    //             $depreciationData['addition_year_depreciation'] = $addition_year_depreciation;
    //             $depreciationData['total_depreciation'] = $total_depreciation;
    //             $depreciationData['book_value'] = $book_value;
    //             $createDepreciation = AssetDepreciationBalance::create($depreciationData);
    //         }
    //         foreach ($assets as $asset) {

    //             $currentAssetDepreciation = AssetDepreciationBalance::where('month', $currentMonth)
    //                 ->where('year', $currentYear)
    //                 ->where('third_account_id', $asset->third_account_id)
    //                 ->where('third_depreciation_account_id', $asset->third_depreciation_account_id)
    //                 ->where('asset_item_id', $asset->asset_item_id)
    //                 ->latest()
    //                 ->first();
    //             $data['asset_item_id'] = $asset->asset_item_id;
    //             $data['third_account_id'] = $asset->third_account_id;
    //             $data['third_depreciation_account_id'] = $asset->third_depreciation_account_id;
    //             $data['month'] = $currentMonth;
    //             $data['year'] = $currentYear;
    //             if ($currentAssetDepreciation) {
    //                 $original_cost = $currentAssetDepreciation->original_cost;
    //                 $addition_year_cost = $asset->total_asset_cost;
    //                 $total_cost = $original_cost + $addition_year_cost;
    //                 $current_month_depreciation = round($total_cost / 12);
    //                 $addition_year_depreciation = round($original_cost / 12);
    //                 $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
    //                 $book_value = $total_cost - $total_depreciation;
    //                 $data['id'] = $currentAssetDepreciation->id;
    //                 $data['date'] = now();
    //                 $data['original_cost'] = $original_cost;
    //                 $data['addition_year_cost'] = $addition_year_cost;
    //                 $data['total_cost'] = $total_cost;
    //                 $data['current_month_depreciation'] = $current_month_depreciation;
    //                 $data['addition_year_depreciation'] = $addition_year_depreciation;
    //                 $data['total_depreciation'] = $total_depreciation;
    //                 $data['book_value'] = $book_value;
    //                 $this->updateOrCreateDepreciationBalance($data);
    //             } else {
    //                 $original_cost = 0;
    //                 $addition_year_cost = $asset->total_asset_cost;
    //                 $total_cost = $original_cost + $addition_year_cost;   //current_depreciation
    //                 $current_month_depreciation = round($total_cost / 12);
    //                 $addition_year_depreciation = 0;
    //                 $total_depreciation = $current_month_depreciation + $addition_year_depreciation;
    //                 $book_value = $total_cost - $total_depreciation;
    //                 $data['id'] = null;
    //                 $data['date'] = $date;
    //                 $data['original_cost'] = $original_cost;
    //                 $data['addition_year_cost'] = $addition_year_cost;
    //                 $data['total_cost'] = $total_cost;
    //                 $data['current_month_depreciation'] = $current_month_depreciation;
    //                 $data['addition_year_depreciation'] = $addition_year_depreciation;
    //                 $data['total_depreciation'] = $total_depreciation;
    //                 $data['book_value'] = $book_value;
    //                 $this->updateOrCreateDepreciationBalance($data);
    //             }
    //         }
    //         DB::commit();
    //         ResponseMessage('Depreciation Balance updated successfully');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         ResponseMessage($e->getMessage(), 402);
    //         throw $e;
    //     }

    // }


    public function addDepreciationBalance($request)
    {
        $year = $request->year;  // The year you want to filter by
        $month = $request->month;    // The month you want to filter by
        $date = convertDateFormat($request->date);
        $now = Carbon::parse($date);
        $prevMonth = Carbon::parse($date)->subMonth();
        $previousMonth = Carbon::parse($date)->subMonth()->format('n');
        $previousYear = Carbon::parse($date)->subMonth()->format('Y');
        $currentYear = Carbon::parse($date)->format('Y');
        $currentMonth = Carbon::parse($date)->format('n');
        $assets = Asset::whereMonth('purchase_date', operator: $prevMonth)
            ->select('id', 'asset_item_id', 'third_account_id', 'third_depreciation_account_id', 'cost', 'useful_life')
            ->get();
        $assetDepreciations = AssetDepreciationBalance::where('month', $previousMonth)
            ->where('year', $previousYear)
            ->join('assets', 'asset_depreciation_balances.asset_id', 'assets.id')
            ->select('asset_depreciation_balances.*', 'assets.useful_life')
            // ->select(
            //     DB::raw('SUM(asset_depreciation_balances.original_cost) as original_cost'),
            //     DB::raw('SUM(asset_depreciation_balances.addition_year_cost) as addition_year_cost'),
            //     DB::raw('SUM(asset_depreciation_balances.addition_year_depreciation) as addition_year_depreciation'),
            //     DB::raw('SUM(asset_depreciation_balances.total_cost) as total_cost'),
            //     DB::raw('SUM(asset_depreciation_balances.current_month_depreciation) as current_month_depreciation'),
            //     DB::raw('SUM(asset_depreciation_balances.total_depreciation) as total_depreciation'),
            //     DB::raw('SUM(asset_depreciation_balances.book_value) as book_value'),
            //     'assets.useful_life',
            //     'asset_depreciation_balances.asset_id',
            //     'asset_depreciation_balances.year',
            //     'asset_depreciation_balances.month',
            //     'asset_depreciation_balances.date',
            // )
            // ->groupBy('assets.id','asset_depreciation_balances.year','asset_depreciation_balances.month','asset_depreciation_balances.date')
            ->get();
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
            Log::info('Db Commit Successfully');
            DB::commit();
            Log::info('Db Commit Successfully');
            ResponseMessage('Depreciation Balance updated successfully');
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
            DB::commit();
            return $assetDepreciationBalance;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getDepreciationBalance($request)
    {
        // $account_code= $request->type=='current_asset' ? []
        $fix_asset_tangiable = '1-1000';
        $fix_asset_untangible = '1-1100';
        $inventory_held = '2-1020';
        $date = Carbon::parse($request->date);
        $month = Carbon::parse($request->date)->format('n');
        $year = Carbon::parse($request->date)->format('Y');
        $subAccountIds = $request->sub_account_id;

        $deptBalance = new \stdClass();
        if ($request->type == 'current_asset') {
            $current_asset = $this->depreciationBalanceQuery($inventory_held, $month, $year);
            $deptBalance->current_asset = $current_asset;
            // $deptBalance->current_asset=$current_asset['data'];
        }
        if ($request->type == 'fix_asset') {
            $fix_asset_tangiable = $this->depreciationBalanceQuery($fix_asset_tangiable, $month, $year);
            $fix_asset_untangible = $this->depreciationBalanceQuery($fix_asset_untangible, $month, $year);
            // $final_original_cost=$final_additon_year=$final_total=$c=$final_depreciation=$final_book_value=0;
            $deptBalance->fix_asset_tangiable = $fix_asset_tangiable;
            $deptBalance->fix_asset_untangible = $fix_asset_untangible;
            $deptBalance->final_original_cost=$fix_asset_tangiable['total_original_cost']+$fix_asset_untangible['total_original_cost'];
            $deptBalance->final_addition_year=$fix_asset_tangiable['total_addition_year']+$fix_asset_untangible['total_addition_year'];
            $deptBalance->final_total=$fix_asset_tangiable['total']+$fix_asset_untangible['total'];
            $deptBalance->final_addition_during_year=$fix_asset_tangiable['total_addition_during_year']+$fix_asset_untangible['total_addition_during_year'];
            $deptBalance->final_depreciation=$fix_asset_tangiable['total_depreciation']+$fix_asset_untangible['total_depreciation'];
            $deptBalance->final_book_value=$fix_asset_tangiable['total_book_value']+$fix_asset_untangible['total_book_value'];
        }
        // ->groupBy('main_account.sub_account_id');
        ResponseData($deptBalance);
    }

    public function depreciationBalanceQuery($account_code, $month, $year)
    {
        $depreciationBalance = $this->depreciationService->depreciationBalanceQuery($account_code, $month, $year);
        $total_original_cost = $total_addition_year = $total = $total_current_month = $total_addition_during_year = $total_depreciation = $total_book_value = 0;
        foreach ($depreciationBalance as $balance) {
            $total_original_cost += $balance->original_cost;
            $total_addition_year += $balance->addition_year_cost;
            $total += $balance->total_cost;
            $total_current_month += $balance->current_month_depreciation;
            $total_addition_during_year += $balance->addition_year_depreciation;
            $total_depreciation += $balance->total_depreciation;
            $total_book_value += $balance->book_value;
        }
        return [
            'data' => $depreciationBalance,
            'total_original_cost' => $total_original_cost,
            'total_addition_year' => $total_addition_year,
            'total' => $total,
            'total_addition_during_year' => $total_addition_during_year,
            'total_depreciation' => $total_depreciation,
            'total_book_value' => $total_book_value,
        ];
        // $depreciationBalance = AssetDepreciationBalance::join('assets', 'asset_depreciation_balances.asset_id', '=', 'assets.id')
        //     ->join('accounts as main_account', 'assets.third_account_id', '=', 'main_account.id')
        //     ->join('accounts as account_depreciation', 'assets.third_depreciation_account_id', '=', 'account_depreciation.id')
        //     // ->join('accounts as main_account', 'assets.third_account_id', '=', 'main_account.id')
        //     ->join('sub_accounts', 'main_account.sub_account_id', '=', 'sub_accounts.id')
        //     ->where('month', $month)
        //     ->where('year', $year)
        //     ->where('sub_accounts.account_code', $account_code)
        //     ->select(
        //         DB::raw('SUM(asset_depreciation_balances.original_cost) as original_cost'),
        //         DB::raw('SUM(asset_depreciation_balances.addition_year_cost) as addition_year_cost'),
        //         DB::raw('SUM(asset_depreciation_balances.addition_year_depreciation) as addition_year_depreciation'),
        //         DB::raw('SUM(asset_depreciation_balances.total_cost) as total_cost'),
        //         DB::raw('SUM(asset_depreciation_balances.current_month_depreciation) as current_month_depreciation'),
        //         DB::raw('SUM(asset_depreciation_balances.total_depreciation) as total_depreciation'),
        //         DB::raw('SUM(asset_depreciation_balances.book_value) as book_value'),
        //         'main_account.name as name',
        //     )
        //     ->groupBy('main_account.id', 'account_depreciation.id')
        //     ->get();

    }

}
