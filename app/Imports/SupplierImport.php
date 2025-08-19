<?php

namespace App\Imports;

use App\Models\Supplier;
use App\Models\SupplierPhone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierBankAccount;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Repositories\Supplier\SupplierRepository;

class SupplierImport implements ToModel, WithHeadingRow,WithBatchInserts, WithChunkReading,SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    protected $supplierRepository;
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }
    public function model(array $row)
    {
        DB::beginTransaction();
        try {

            if (!isset($row['name']) || $row['name'] === null || trim($row['name']) === '' || $row['supplier_ap_name']==null) {
                DB::rollback();
                return null;
            }
            $creditOpeningDate = null;
            if (isset($row['credit_opening_date']) && is_numeric($row['credit_opening_date'])) {
                try {
                    $creditOpeningDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['credit_opening_date']);
                } catch (\Exception $e) {
                    $creditOpeningDate = now();
                }
            } else {
                $creditOpeningDate = now();
            }

            $mockRequest = (object) ['name' => $row['supplier_ap_name']];

            $account = $this->supplierRepository->createSupplierAccount($mockRequest);
            $otherPayable = $account['other_payable'];
            $creditor = $account['creditor'];

            $exactDate = null;
            if (isset($row['exact_date']) && $row['exact_date'] !== 'null') {
                $decodedExactDate = json_decode($row['exact_date'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for exact_date.', 400);
                }
                $exactDate = json_encode($decodedExactDate);
            }

            $supplier = Supplier::firstOrCreate(
                [
                    'supplier_code' => $row['supplier_code'], // 👈 unique lookup
                ],
                [
                    'account_id'            => $otherPayable->id,
                    'creditor_account_id'   => $creditor->id,
                    'name'                  => trim((string) $row['name']),
                    'shop_name'             => $row['shop_name'],
                    'address'               => $row['address'],
                    'email'                 => $row['email'],
                    'credit_limit'          => $row['credit_limit'],
                    'credit_opening_date'   => $creditOpeningDate,
                    'credit_opening_amount' => $row['credit_opening_amount'],
                    'lead_time_day'         => $row['lead_time_day'] ?? null,
                    'lead_time_hour'        => $row['lead_time_hour'] ?? null,
                    'lead_time_minutes'     => $row['lead_time_minutes'] ?? null,
                    'credit_term_type'      => $row['credit_term_type'],
                    'day'                   => $row['day'] ?? null,
                    'amount_limitation'     => $row['amount_limitation'] ?? null,
                    'exact_date'            => $exactDate,
                ]
            );
            // $supplier = new Supplier([
            //     'supplier_code'=>$row['supplier_code'],
            //     'account_id' => $otherPayable->id,
            //     'creditor_account_id' => $creditor->id,
            //     'name' => trim((string)$row['name']),
            //     'shop_name' =>$row['shop_name'],
            //     'address' =>$row['address'],
            //     'email' =>$row['email'],
            //     'credit_limit' =>$row['credit_limit'],
            //     'credit_opening_date' =>$creditOpeningDate,
            //     'credit_opening_amount' =>$row['credit_opening_amount'],
            //     'lead_time_day' => $row['lead_time_day'] ?? null,
            //     'lead_time_hour' => $row['lead_time_hour'] ?? null,
            //     'lead_time_minutes' => $row['lead_time_minutes'] ?? null,
            //     'credit_term_type' => $row['credit_term_type'],
            //     'day' => $row['day'] ?? null,
            //     'amount_limitation' => $row['amount_limitation'] ?? null,
            //     'exact_date' => $exactDate,
            // ]);

            $supplier->save();

            if (isset($row['supplier_phones'])) {
                $supplierPhones = json_decode($row['supplier_phones'], true);
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

            if (isset($row['supplier_bank_accounts'])) {
                $supplierBankAccounts = json_decode($row['supplier_bank_accounts'], true);
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

            if (isset($row['credit_opening_amount']) && isset($row['credit_opening_date'])) {
                $this->supplierRepository->createPayableTransaction($supplier);
            }
            DB::commit();
            return $supplier;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
