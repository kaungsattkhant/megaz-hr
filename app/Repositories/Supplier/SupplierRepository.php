<?php

namespace App\Repositories\Supplier;

use App\Models\Account;
use App\Models\Supplier;
use App\Imports\BrandImport;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use App\Models\SupplierPhone;
use App\Models\AccountPayable;
use App\Imports\SupplierImport;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierBankAccount;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Http\Action\Transaction\StoreTransactionLedger;

class SupplierRepository implements SupplierInterface
{
    public function list($request)
    {
        $query = Supplier::with(['account', 'items', 'supplierPhone', 'supplierBankAccount'])->orderBy('id', 'DESC');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('shop_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('address', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('credit_limit', 'LIKE',    '%' . $searchTerm . '%');
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

            if (isset($data['credit_term_type'])) {
                switch ($data['credit_term_type']) {
                    case "day":
                        $data['amount_limitation'] = null;
                        $data['exact_date'] = null;
                        break;
                    case "amount_limitation":
                        $data['day'] = null;
                        $data['exact_date'] = null;
                        break;
                    case "exact_date":
                        $data['day'] = null;
                        $data['amount_limitation'] = null;
                        if (isset($data['exact_date']) && $data['exact_date'] !== 'null') {
                            $decodedExactDate = json_decode($data['exact_date'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                return ResponseMessage('Invalid JSON data provided for exact_date.', 400);
                            }
                            $data['exact_date'] = json_encode($decodedExactDate);
                        }
                        break;
                }
            }
            $supplier = Supplier::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            //remove supplier item at 
            // $decodedSupplierItems = json_decode($request->supplier_items);

            // if (json_last_error() !== JSON_ERROR_NONE) {
            //     return ResponseMessage('Invalid JSON data provided for supplier items.', 400);
            // }


            // if (empty($decodedSupplierItems)) {
            //     ResponseMessage('Supplier Item is empty', 419);
            // }
            // $syncData = [];
            // foreach ($decodedSupplierItems as $supplierItems) {
            //     if (isset($request->id)) {
            //         if (!isset($supplierItems->id)) {
            //             $isExistSupplierItem = SupplierItem::where('supplier_id', $supplier->id)
            //                 ->where('item_id', $supplierItems->item_id)
            //                 ->where('brand_id', $supplierItems->brand_id)
            //                 ->exists();
            //             if ($isExistSupplierItem) {
            //                 ResponseMessage('Brand and Item are already created to this supplier', 419);
            //             }
            //             $syncData[] = [
            //                 'supplier_id' => $supplier->id,
            //                 'item_id' => $supplierItems->item_id,
            //                 'brand_id' => $supplierItems->brand_id,
            //             ];
            //         }
            //     } else {
            //         // $supplieItem=SupplierItem::create([
            //         //     'supplier_id'=>$supplier->id,
            //         //     'item_id'=>$supplierItems->item_id,
            //         //     'brand_id'=>$supplierItems->brand_id,
            //         // ]);
            //         $syncData[] = [
            //             'supplier_id' => $supplier->id,
            //             'item_id' => $supplierItems->item_id,
            //             'brand_id' => $supplierItems->brand_id,
            //         ];
            //     }
            // }
            // DB::table('supplier_items')->insert($syncData);

            if (isset($request->supplier_phones)) {
                $supplierPhones = json_decode($request->supplier_phones, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for supplier phone.', 400);
                }
                foreach ($supplierPhones as $phone) {
                    SupplierPhone::updateOrCreate(
                        [
                            'supplier_id' => $supplier->id,
                            'id' => $phone['id'] ?? null,
                        ],
                        [
                            'phone_number' => $phone['phone_number'],
                            'type' => $phone['type'],
                        ]
                    );
                }
            }

            if (isset($request->supplier_bank_accounts)) {
                $supplierBankAccounts = json_decode($request->supplier_bank_accounts, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for supplier bankaccount.', 400);
                }
                foreach ($supplierBankAccounts  as $supplierBankAccount) {
                    SupplierBankAccount::updateOrCreate(
                        [
                            'supplier_id' => $supplier->id,
                            'id' => $supplierBankAccount['id'] ?? null,
                        ],
                        [
                            'account_name' => $supplierBankAccount['account_name'],
                            'account_number' => $supplierBankAccount['account_number'],
                        ]
                    );
                }
            }
            if (isset($request->credit_opening_amount) && isset($request->credit_opening_date)) {
                $this->createPayableTransaction($supplier);
            }

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
        $supplier->load('supplier_items.brand', 'supplier_items.item');
        // $supplier->load('supplier_items.item');
        // $supplier->supplier_items = $supplier->supplier_items;
        $supplier->account = $supplier->account;
        $supplier->creditor_account = $supplier->creditor_account;
        $supplier->supplierPhone = $supplier->supplierPhone;
        $supplier->supplierBankAccount = $supplier->supplierBankAccount;
        return $supplier;
    }

    public function createSupplierAccount($request)
    {
        DB::beginTransaction();
        try {
            if($request->name==null){
                // dd($request);
            }
            $otherPayableCode = config('common.payable_account_code'); // '4-4000',
            $creditorCode = config('common.creditor_account_code');
            $otherPayable = $this->createAccountBySubAccount('Other Payable-' . $request->name, $otherPayableCode);
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

    public function createPayableTransaction($supplier)
    {
        DB::beginTransaction();
        try {

            $accountPayable = AccountPayable::updateOrCreate(
                [
                    'supplier_id' => $supplier->id,
                    'account_id' => $supplier->account_id
                ],
                [
                    'type' => 'addition',
                    'date_time' => $supplier->credit_opening_date,
                    'amount' => $supplier->credit_opening_amount,
                    'supplier_id' => $supplier->id,
                    'account_id' => $supplier->account_id,
                    'created_by' => UserData()->id,
                ]
            );
            // $data = $request->all();
            // $data['created_by'] = UserData()->id;
            // $data['is_confirmed'] = 1;
            // $transaction = (new StoreTransactionLedger())->createTransaction($data);
            // $creditLedger = (new StoreTransactionLedger())->storeLedger([
            //     'date' => now(),
            //     'value' => $request->value,
            //     'transaction_id' => $transaction->id,
            //     'account_id' => $request->cash_account_id,
            //     'personable_id' => $request->supplier_id,
            //     'personable_type' => 'supplier',
            //     'action' => 'credit',
            // ]);

            // #debit
            // $debitLedger = (new StoreTransactionLedger())->storeLedger([
            //     'value' => $request->value,
            //     'transaction_id' => $transaction->id,
            //     'account_id' => $request->account_id,
            //     'personable_id' => $request->supplier_id,
            //     'personable_type' => 'supplier',
            //     'action' => 'debit',
            // ]);
            DB::commit();
            return $accountPayable;
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

    public function toggleBrandItem($supplieItemId)
    {
        if (toggleColumn(SupplierItem::class, $supplieItemId, 'is_active')) {
            ResponseMessage('SupplierItem status toggled successfully.', 200);
        } else {
            ResponseMessage('SupplierItem not found.', 404);
        }
    }

    public function toggleSupplierPhone($supplierPhoneId)
    {
        if (toggleColumn(SupplierPhone::class, $supplierPhoneId, 'is_active')) {
            ResponseMessage('Supplier Phone status toggled successfully.', 200);
        } else {
            ResponseMessage('Supplier Phone not found.', 404);
        }
    }

    public function toggleSupplierBankAccount($supplierBankAccountId)
    {
        if (toggleColumn(SupplierBankAccount::class, $supplierBankAccountId, 'is_active')) {
            ResponseMessage('Supplier Bank Account status toggled successfully.', 200);
        } else {
            ResponseMessage('Supplier Bank Account not found.', 404);
        }
    }

    public function supplierImport($request)
    {
        $file = $request->file('sheet');
        $headings = (new HeadingRowImport)->toArray($file);
        Excel::import(new SupplierImport($this), $file);
        ResponseMessage('Import Successfully', 200);
    }

    public function brandImport($request)
    {
        $file = $request->file('sheet');
        Excel::import(new BrandImport(), $file);
        ResponseMessage('Import Successfully', 200);
    }
}
