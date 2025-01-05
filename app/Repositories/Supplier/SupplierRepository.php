<?php

namespace App\Repositories\Supplier;

use App\Models\Account;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SupplierRepository implements SupplierInterface
{

    public function list($request)
    {
        $query = Supplier::with(['account', 'items'])->orderBy('id', 'DESC');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('shop_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('address', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('credit_limit', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('per_page') || $request->has('page')) {
            return $query->paginate(config('common.list_count'));
        }

        return $query->get();
    }


    public function updateOrCreate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $supplier = Supplier::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            $brandIds = $request->brands;// [1,2]
            $itemIds = $request->items;//[6]

            // foreach ($brandIds as $brandId) {
            //     // $supplier->items()->sync($request->items);
            //     $supplier->items()->syncWithPivotValues($itemIds, ['brand_id' => $brandId]);
            // }




            // if ($request->id) {
            //     $supplier->items()->detach(); // Detaches all relationships
            // }

            $syncData = [];
            $uniqueCombinations = [];
            // foreach ($itemIds as $itemId) {
            //     foreach ($brandIds as $brandId) {
            //         $syncData[] = [
            //             'item_id' => $itemId,
            //             'brand_id' => $brandId,
            //         ];
            //     }
            // }
            foreach ($itemIds as $itemId) {
                foreach ($brandIds as $brandId) {
                    $combinationKey = $supplier->id.'_'.$itemId . '_' . $brandId; // Create a unique key
                    if (!isset($uniqueCombinations[$combinationKey])) {
                        $syncData[] = [
                            'supplier_id'=>$supplier->id,
                            'item_id' => $itemId,
                            'brand_id' => $brandId,
                        ];
                        $uniqueCombinations[$combinationKey] = true; // Mark this combination as added
                    }
                }
            }
            // Bulk insert into the pivot table
            DB::table('supplier_items')->insert($syncData);


            DB::commit();
            return $supplier;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($supplier)
    {
        $supplier->items = $supplier->items;
        return $supplier;
    }

    public function createSupplierAccount($request)
    {
        DB::beginTransaction();
        try {
            $otherPayableCode = config('common.payable_account_code');
            $creditorCode = config('common.creditor_account_code');
            $otherPayable = $this->createAccountBySubAccount('Other Payable-'.$request->name, $otherPayableCode);
            $creditor = $this->createAccountBySubAccount($request->name, $creditorCode);

            if ($otherPayable && $creditor) {
                DB::commit();
                return ['other_payable' => $otherPayable, 'creditor' => $creditor];
            }
            ResponseMessage('Something is wrong', 419);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }


    }

    public function createAccountBySubAccount($name, $subAccountCode)
    {
        $latestAccount = Account::whereHas('sub_account', function ($q) use ($subAccountCode) {
            $q->where('account_code', $subAccountCode);
        })
            ->orderByRaw("CAST(SUBSTRING_INDEX(account_code, '-', -1) AS UNSIGNED) DESC")
            ->first();
        // ->max('account_code');
        if ($latestAccount) {
            $latestAccountCodeNo = explode('-', $latestAccount->account_code);
            $new_account_code = (int) $latestAccountCodeNo[1] + 1;
            $code = $latestAccountCodeNo[0] . '-' . $new_account_code;
            $account = Account::create([
                'name' => $name,
                'account_code' => $code,
                'sub_account_id' => $latestAccount->sub_account_id,
            ]);
            return $account;
        }
    }

}
