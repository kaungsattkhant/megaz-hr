<?php

namespace App\Imports;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\UomConversion;
use App\Models\InventoryLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class RawItemImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, SkipsEmptyRows
{
    use Importable, SkipsErrors, SkipsFailures;
    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            Log::info($row);
            $rawDate = $row['date'];
            if (is_numeric($rawDate)) {
                $convertedDate = ExcelDate::excelToDateTimeObject($rawDate);
            } else {
                $convertedDate = \Carbon\Carbon::parse($rawDate);
            }
            $inventory = Inventory::where('id', $row['inventory_id'])->first();
            if (!$inventory) {
                ResponseMessage( $row['inventory_id'].' - Inventory Code is invalid', 419);
            }

            $item = Item::where('code', $row['item_code'])->first();
            if(!$item){
                ResponseMessage( $row['item_code'].' - Item Code is invalid', 419);
            }
            if(isset($row['base_uom_code'])){
                $baseUom = Uom::where('uom_code', $row['base_uom_code'])->first();
                if(!$baseUom){
                    ResponseMessage( $row['base_uom_code'].' - Base Uom Code is invalid', 419);
                }
                if($baseUom->id !== $item->base_uom_id){
                    ResponseMessage( $row['base_uom_code'].' - is not base uom of '.$item->name, 419);
                }
            }
            if(isset($row['uom_code'])){
                $uom = Uom::where('uom_code', $row['uom_code'])->first();
                if(!$uom){
                    ResponseMessage( $row['uom_code'].' - Uom Code is invalid', 419);
                }
                if($uom->id !== $item->uom_id){
                    ResponseMessage( $row['uom_code'].' - is not uom of '.$item->name, 419);
                }
            }

            if (!$item) {
                ResponseMessage('Item Code is invalid', 419);
            }
            $quantity = ((float)$row['base_uom_quantity'] * $item->conversion) + (float)$row['uom_quantity'];
            $inventoryLedger = InventoryLedger::create([
                'inventory_id' => $inventory->id,
                'date' => $convertedDate,
                'action' => 'in',
            ]);

            $batchNo = now()->format('YmdHis') .'_'.$item->id .'_'. $inventoryLedger->id;
            $inventoryLedger->batch_no = $batchNo;
            $inventoryLedger->save();
            $inventoryLedger->inventory_ledger_items()->create([
                'inventory_id' => $inventory->id,
                'item_id' => $item->id,
                'inventory_ledger_id' => $inventoryLedger->id,
                'quantity' => $quantity,
            ]);
            DB::commit();
            return null;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            ResponseMessage('Failed to import Ready to Sale Item', 419);
        }
    }

    public function rules(): array
    {
        return [];
    }


    //Controls how many records are inserted or updated in a single operation.
    public function batchSize(): int
    {
        return 500;
    }

    //Chunk size: Controls how much data is fetched or processed at a time.
    public function chunkSize(): int
    {
        return 500;
    }
}
