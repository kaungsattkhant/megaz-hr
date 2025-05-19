<?php

namespace App\Imports;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemType;
use App\Models\UomConversion;
use App\Services\ItemService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, SkipsEmptyRows
{


    use Importable, SkipsErrors, SkipsFailures;
    private $itemService;
    private $importedCodes = [];
    private $importedNames = [];
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }



    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            $itemCode = Item::where('code',  $row['code'])->first();
            if ($itemCode) {
                return ResponseMessage('Duplicate itemcode.',  419);
            }
            $categoryId = Category::where('category_code', $row['category_code'])->value('id');
            $itemTypeId = ItemType::where('item_type_code', $row['item_type_code'])->value('id');
            $baseUomId = Uom::where('uom_code', $row['base_uom_code'])->value('id');
            $uomId = Uom::where('uom_code', $row['uom_code'])->value('id');
            $minHoldingBaseUomQuantity = $row['min_holding_base_uom_quantity'] ?? 0;
            $minHoldingUomQuantity = $row['min_holding_uom_quantity'] ?? 0;
            $uomConversion = $this->itemService->uomConversionRate($baseUomId, $uomId);
            if (!$uomConversion) {
                return ResponseMessage('No UOM conversion found for the given units.', 404);
            }
            $conversionRate = $uomConversion->conversion;
            if ($conversionRate <= 0) {
                return ResponseMessage('Invalid conversion rate.', 404);
            }
            $minimumHoldingAmount = $this->itemService->calculateMinimumHoldingAmount(
                $minHoldingBaseUomQuantity,
                $minHoldingUomQuantity,
                $conversionRate
            );
            $item = new Item([
                'name' => $row['name'],
                'code' => $row['code'],
                'category_id' => $categoryId,
                'item_type_id' => $itemTypeId,
                'base_uom_id' => $baseUomId ?? null,
                'uom_id' => $uomId ?? null,
                'min_holding_base_uom_quantity' => $minHoldingBaseUomQuantity ?? 0,
                'min_holding_uom_quantity' => $minHoldingUomQuantity ?? 0,
                'minimum_holding_amount' =>  $minimumHoldingAmount  ?? 0,
            ]);

            $item->save();
            DB::commit();

            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            // '*.name' => ['required', 'string'],
            // '*.code' => [
            //     'required',
            //     'string',
            //     'unique:items,code',
            //     Rule::notIn($this->importedCodes),
            // ],
            // '*.category_id' => [
            //     'required',
            //     'string',
            //     Rule::exists('categories', 'category_code'),
            // ],
            // '*.item_type_id' => [
            //     'required',
            //     'string',
            //     Rule::exists('item_types', 'item_type_code'),
            // ],
            // '*.base_uom_id' => [
            //     'required',
            //     'string',
            //     Rule::exists('uoms', 'uom_code'),
            // ],
            // '*.uom_id' => [
            //     'required',
            //     'string',
            //     Rule::exists('uoms', 'uom_code'),
            // ],
            // '*.min_holding_base_uom_quantity' => [
            //     'required',
            //     'numeric',
            //     'min:0',
            // ],
            // '*.min_holding_uom_quantity' => [
            //     'required',
            //     'numeric',
            //     'min:0',
            // ],
            // '*.minimum_holding_amount' => [
            //     'required',
            //     'numeric',
            //     'min:0',
            // ],
        ];
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
