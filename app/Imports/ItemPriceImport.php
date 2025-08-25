<?php

namespace App\Imports;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ItemPrice;
use App\Models\ItemType;
use App\Models\Supplier;
use App\Models\SupplierItem;
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

class ItemPriceImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, SkipsEmptyRows
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
        if (empty($row['item_code']) || empty($row['brand_id']) || empty($row['supplier_code']) || empty($row['uom_code']) || empty($row['price'])) {
            return null; // Returning null skips the row
        }
        DB::beginTransaction();
        try {
            // if(!isset($row['item_code'])){
            //     dd($row);
            // }
            $item = Item::where('code', $row['item_code'])->first();
            $brand = Brand::where('id', $row['brand_id'])->first();
            $supplier = Supplier::where('supplier_code', $row['supplier_code'])->first();
            if (!$supplier) {
                // DB::rollback();
                // return null;
            }
            $uom = Uom::where('uom_code', $row['uom_code'])->first();
            if (!$item) {
                ResponseMessage('Item Code is invalid', 419);
            }
            if (!$brand) {
                ResponseMessage('Brand Id is invalid', 419);
            }
            if (!$supplier) {
                ResponseMessage('Supplier Id is invalid', 419);
            }
            $supplierItem = SupplierItem::create([
                'supplier_id' => $supplier->id,
                'item_id' => $item->id,
                'brand_id' => $brand->id,
            ]);
            $uomPrice = $row['price'];
            $type = null;
            $price = $row['price'];
            if ($item->base_uom_id == $uom->id) {
                $type = 'base_uom';
            } elseif ($item->uom_id == $uom->id) {
                $type = 'uom';
                $price = $item->uom_conversion * (float)$row['price'];
            } else {
                ResponseMessage('Uom does not match with item uom', 419);
            }
            if (!$type) {
                ResponseMessage('Uom Type is invalid', 419);
            }
            $itemPrice = ItemPrice::create([
                'price' => $price,
                'supplier_item_id' => $supplierItem->id,
                'uom_id' => $uom->id,
                'type' => $type,
                'uom_price' => $uomPrice,
            ]);
            $item->brands()->sync([$brand->id]);
            DB::commit();
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
