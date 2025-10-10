<?php

namespace App\Imports;

use App\Models\Menu;
use App\Models\MenuStep;
use App\Models\Inventory;
use App\Models\MenuStepItem;
use App\Models\InventoryLedger;
use Illuminate\Support\Collection;
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

class ReadyToSaleItemImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, SkipsEmptyRows
{
    use Importable, SkipsErrors, SkipsFailures;
    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            Log::info($row);
            $rawDate = $row['date'];;
            if (is_numeric($rawDate)) {
                $convertedDate = ExcelDate::excelToDateTimeObject($rawDate);
            } else {
                $convertedDate = \Carbon\Carbon::parse($rawDate);
            }
            $inventory = Inventory::where('id', $row['inventory_id'])->first();
            if (!$inventory) {
                ResponseMessage( $row['inventory_id'].' - Inventory Code is invalid', 419);
            }

            $menuStepItems = MenuStepItem::join('menu_steps', 'menu_step_items.menu_step_id', '=', 'menu_steps.id')
            ->join('menus', 'menu_steps.menu_id', '=', 'menus.id')
            ->where('menus.code', $row['menu_code'])
            ->where('menu_steps.type',  "ready_to_sale")
            ->select('menu_step_items.*')
            ->get();
            Log::info($menuStepItems);
            
            if ($menuStepItems->isEmpty()) {
                ResponseMessage( $row['menu_code'].' - Ready to sale Item is invalid', 419);
            }

            $inventoryLedger = InventoryLedger::create([
                'inventory_id' => $inventory->id,
                'date' => $convertedDate,
                'action' => 'in',
            ]);
            // $batchNo = now()->format('YmdHis') .'_'. $inventory->id .'_'. $inventoryLedger->id;
            // $inventoryLedger->batch_no = $batchNo;
            // $inventoryLedger->save();

            foreach ($menuStepItems as $menuStepItem) {
                $inventoryLedger->inventory_ledger_items()->create([
                    'inventory_id' => $inventory->id,
                    'item_id' => $menuStepItem->item_id,
                    'inventory_ledger_id' => $inventoryLedger->id,
                    'quantity' => (float)$row['quantity'] * (float)$menuStepItem->quantity,
                ]);
            }
            DB::commit();
            return null;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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
