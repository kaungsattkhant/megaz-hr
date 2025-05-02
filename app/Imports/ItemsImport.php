<?php

namespace App\Imports;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemType;
use App\Models\UomConversion;
use App\Services\ItemService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
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
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, SkipsEmptyRows
{


    use Importable, SkipsErrors, SkipsFailures;
    private $itemService;
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }


    private $importedCodes = [];
    private $importedNames = [];

    public function model(array $row)
    {

        $categoryId = Category::where('category_code', $row['category_code'])->value('id');
        $itemTypeId = ItemType::where('item_type_code', $row['item_type_code'])->value('id');
        $baseUomId = Uom::where('uom_code', $row['base_uom_code'])->value('id') ?? null;
        $uomId = Uom::where('uom_code', $row['uom_code'])->value('id') ?? null;
        $minHoldingBaseUomQuantity = $row['min_holding_base_uom_quantity'] ?? 0;
        $minHoldingUomQuantity = $row['min_holding_uom_quantity'] ?? 0;
        // $uomConversion = $this->itemService->uomConversionRate($baseUomId, $uomId);
        $uomConversion = UomConversion::where('base_unit_id', $baseUomId)
            ->where('conversion_unit_id', $uomId)
            ->where('is_active', 1)
            ->first();
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
        return new Item([
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
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'code' => [
                'required',
                Rule::unique('items', 'code'),
                function ($attribute, $value, $fail) {
                    if (in_array($value, $this->importedCodes)) {
                        $fail("The $attribute '{$value}' is duplicated in the file.");
                    } else {
                        $this->importedCodes[] = $value;
                    }
                },
            ],
            'category_code' => 'required|string|max:255',
            'item_type_code' => 'required',
            'base_uom_code' => 'required',
            'uom_code' => '',
            'min_holding_base_uom_quantity' => 'required',
            'min_holding_uom_quantity' => '',
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
