<?php

namespace App\Repositories\FixedAssetPurchase;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\FixedAssetDepreciation;
use App\Models\FixedAssetPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Provider\Time\FixedTimeProvider;

class FixedAssetPurchaseRepository implements FixedAssetPurchaseRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = FixedAssetPurchase::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $fixedAssetPurchase = FixedAssetPurchase::skip($skip)
                ->take($perPage)
                ->get();
            $fixedAssetPurchase = MakePaginationData($request, $totalCount, 'fixedAssetPurchase', $fixedAssetPurchase);
            return $fixedAssetPurchase;
        } else {
            $fixedAssetPurchase = FixedAssetPurchase::all();
            return $fixedAssetPurchase;
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['date'] = CurrentTime();
            $data['remaining_price'] = $data['total_price'];
            $data['remaining_duration'] = $data['total_duration'];
            $data['start_date'] = CurrentDate();
            $fixedAssetPurchase = FixedAssetPurchase::create($data);
            $fixedAssetPurchase->fixed_asset_id = sprintf('%05d', $fixedAssetPurchase->id);
            $fixedAssetPurchase->save();
            DB::commit();
            return $fixedAssetPurchase;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateIsCheck($request)
    {
        $staff = UserData();
        DB::beginTransaction();
        try {
            $fixedAssetPurchase = FixedAssetPurchase::find($request->id);
            $this->validateModel($fixedAssetPurchase, $staff, 'fixed_asset_purchase');

            if ($staff->hasRoles('Manager')) {
                $fixedAssetPurchase->manager_check_id = $staff->id;
                $fixedAssetPurchase->manager_check_time = CurrentTime();
                $fixedAssetPurchase->status = 'manager checked';
            } elseif ($staff->hasRoles('MD')) {
                if($fixedAssetPurchase->manager_check_time !== null){
                    $fixedAssetPurchase->md_check_time = CurrentTime();
                    $fixedAssetPurchase->is_md_checked = 1;
                    $fixedAssetPurchase->status = 'md checked';
                }else{
                    ResponseMessage('Manager not checked');
                }

            }
            $fixedAssetPurchase->save();
            DB::commit();
            ResponseMessage('Updated successfully');
        } catch (\Throwable $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function fixedAssetBought($request)
    {
        $staff = UserData();
        DB::beginTransaction();

        try {
            $fixedAssetPurchase = FixedAssetPurchase::find($request->id);
            $this->validateModel($fixedAssetPurchase, $staff, 'fixed_asset_bought');

            if ($fixedAssetPurchase) {
                if ($fixedAssetPurchase->is_md_checked !== 0) {
                    if($fixedAssetPurchase->is_bought==1)
                    {
                        ResponseMessage('This fixed asset is already bought .');
                    }
                    $fixedAssetPurchase->bought_by = $staff->id;
                    $fixedAssetPurchase->is_bought = 1;
                    $fixedAssetPurchase->status= 'bought';
                    $fixedAssetPurchase->save();
                    $fixedAssetDepreciation = FixedAssetDepreciation::create([
                        'fixed_asset_purchase_id' => $fixedAssetPurchase->id,
                        'date' => CurrentTime(),
                        'depreciated_amount' => $fixedAssetPurchase->depreciation_amount,
                    ]);

                    $accountData = Account::create([
                        'account_code' => '1-' . $fixedAssetPurchase->fixed_asset_id,
                        'name' => $fixedAssetPurchase->name,
                        'sub_account_id' => 6,
                        'is_available' => 1
                    ]);

                    $transaction = (new StoreTransactionLedger())->createTransaction([
                        'date' => now(),
                        'created_by' => $staff->id,
                        'transactionable_id' => $fixedAssetPurchase->id,
                        'transactionable_type' => 'fixed_asset_purchase',
                        'description' => $fixedAssetPurchase->description,
                        'is_confirmed' => 1
                    ]);

                    $officeCashAccount = Account::where('account_code', '2-1001')->first();
                    $creaditFixedAssetPurchase = (new StoreTransactionLedger())->storeLedger([
                        'value' => $fixedAssetPurchase->total_price,
                        'transaction_id' => $transaction->id,
                        'account_id' => $officeCashAccount->id,
                        'action' => 'credit',
                        'is_cashier_confirmed' => 1
                    ]);

                    $debitFixedAssetPurchase = (new StoreTransactionLedger())->storeLedger([
                        'value' => $fixedAssetPurchase->total_price,
                        'transaction_id' => $transaction->id,
                        'account_id' => $accountData->id,
                        'action' => 'debit',
                        'is_cashier_confirmed' => 1
                    ]);

                    DB::commit();
                    ResponseMessage('Bought successfully',200);
                } else {
                    ResponseMessage('Selected Fixed Asset Purchase is not even checked by staff');
                }
            } else {
                ResponseMessage('Fixed Asset Purchase not found');
            }
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }


    public function validateModel($model, $staff, $type)
    {
        if ($model) {
            if ($type == 'fixed_asset_purchase') {
                if (!($staff->hasRoles('Manager') xor $staff->hasRoles('MD'))) {
                    ResponseMessage("Permission isn't allowed", 422);
                }

                if ($staff->hasRoles('Manager')) {
                    if ($model->manager_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Manager', 419);
                    }

                }else if ($staff->hasRoles('MD')) {
                    if ($model->is_md_checked) {
                        ResponseMessage('This Purchase Order is already checked By MD', 422);
                    }
                }
            }
        }
    }
}
