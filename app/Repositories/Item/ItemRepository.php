<?php

namespace App\Repositories\Item;

use Exception;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemType;
use App\Models\ItemPrice;
use App\Imports\UomsImport;
use App\Imports\ItemsImport;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use App\Models\UomConversion;
use App\Services\ItemService;
use App\Imports\CategoryImport;
use App\Imports\ItemTypeImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\HeadingRowImport;
use App\Services\AveragePriceCalculator;
use Illuminate\Database\Eloquent\Builder;

class ItemRepository implements ItemRepositoryInterface
{
    protected $averagePriceCalculator;
    protected $itemService;
    public function __construct(AveragePriceCalculator $repo, ItemService $itemService)
    {
        $this->averagePriceCalculator = $repo;
        $this->itemService = $itemService;
    }

    public function listAllData(Request $request)
    {
        $category_id = $request->category_id;
        if ($request->per_page || $request->page) {
            return Item::with([
                'category',
                'supplier_items.brand',
                'supplier_items.item_price' => function ($query) {
                    $query->orderByDesc('id');
                }
            ])
                ->when($category_id, function ($q) use ($category_id) {
                    $q->where('items.category_id', $category_id);
                })
                // ->withAveragePrice()
                ->orderByDesc('id')
                ->paginate(config('common.list_count'));
        } else {
            return Item::with([
                'category',
                'supplier_items.brand',
                'supplier_items.item_price' => function ($query) {
                    $query->orderByDesc('id');
                }
            ])
                ->when((isset($request->category_id) && $category_id), function ($q) use ($category_id) {
                    $q->where('items.category_id', $category_id);
                })
                // ->withAveragePrice()
                ->orderByDesc('id')
                ->get();
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {

            if (!isset($data['base_uom_id']) || !isset($data['uom_id'])) {
                return ResponseMessage('Required UOM data is missing.', 400);
            }
            if (!isset($data['min_holding_base_uom_quantity']) || !isset($data['min_holding_uom_quantity'])) {
                return ResponseMessage('Required holding quantities are missing.', 400);
            }

            $baseUomId = $data['base_uom_id'];
            $uomId = $data['uom_id'];
            $uomConversion = UomConversion::where('base_unit_id', $baseUomId)
                ->where('conversion_unit_id', $uomId)
                ->where('is_active', 1)
                ->first();

            if (!$uomConversion) {
                return ResponseMessage('No UOM conversion found for the given units.', 404);
            }

            $conversionRate = $uomConversion->conversion;
            $minimumHoldingAmount = $this->itemService->calculateMinimumHoldingAmount(
                $data['min_holding_base_uom_quantity'],
                $data['min_holding_uom_quantity'],
                $conversionRate
            );
            $data['minimum_holding_amount'] = $minimumHoldingAmount;

            $item = Item::firstOrCreate(['name' => $data['name'], 'code' => $data['code']], $data);
            if (isset($data['brand_id'])) {
                $item->brands()->sync($data['brand_id']);
            }
            DB::commit();
            ResponseData($item);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateData(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $item = Item::find($id);
            if (!$item) {
                return ResponseMessage('Item not found.', 404);
            }

            if ($item) {
                if (isset($data['base_uom_id']) && isset($data['uom_id'])) {
                    $baseUomId = $data['base_uom_id'];
                    $uomId = $data['uom_id'];
                    $uomConversion = $this->itemService->uomConversionRate($baseUomId, $uomId);
                    if (!$uomConversion) {
                        return ResponseMessage('No UOM conversion found for the given units.', 404);
                    }
                    $conversionRate = $uomConversion->conversion;

                    if (isset($data['min_holding_base_uom_quantity']) && isset($data['min_holding_uom_quantity'])) {
                        $minimumHoldingAmount = $this->itemService->calculateMinimumHoldingAmount(
                            $data['min_holding_base_uom_quantity'],
                            $data['min_holding_uom_quantity'],
                            $conversionRate
                        );
                        $data['minimum_holding_amount'] = $minimumHoldingAmount;
                    }
                }
                $item->update($data);
                if (isset($data['brand_id'])) {
                    $item->brands()->sync($data['brand_id']);
                }
            }
            DB::commit();
            ResponseData($item);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }


    // private function calculateMinimumHoldingAmount($minHoldingBaseUomQuantity, $minHoldingUomQuantity, $conversionRate)
    // {
    //     $minHoldingBaseUomQuantity = (float) $minHoldingBaseUomQuantity;
    //     $minHoldingUomQuantity = (float) $minHoldingUomQuantity;
    //     $conversionRate = (float) $conversionRate;

    //     return ($minHoldingBaseUomQuantity * $conversionRate) + $minHoldingUomQuantity;
    // }

    public function addPriceItem($request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            if ((isset($data['type']) &&  $data['type'] === 'uom')) {
                $data['price'] = $data['uom_conversion'] * $data['uom_price'];
            } else if ((isset($data['type']) && $data['type'] === 'base_uom')) {
                $data['price'] = $data['uom_price'];
            }
            $createdItemPrice = ItemPrice::create($data);
            DB::commit();
            return $createdItemPrice;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        $item = Item::find($id);
        if ($item) {
            $item->is_active = 0;
            return true;
        } else {
            return false;
        }
    }

    public function getItemPriceListByItem($item_id)
    {
        return ItemPrice::with('uom')->orderBy('id', 'desc')->where('item_id', $item_id)->paginate(20);
    }

    public function getItemType()
    {
        return ItemType::all();
    }

    public function supplierByItem($itemId)
    {
        // $supplierByItem = SupplierItem::with('supplier', 'item','brand')
        //     ->where('item_id',  $itemId)
        //     // ->groupBy('supplier_id')
        //     ->get();
        $supplierByItem = SupplierItem::with('supplier', 'item', 'brand')
            ->where('item_id', $itemId)
            ->select('supplier_id', DB::raw('MAX(id) as id')) // Use MAX(id) to pick a unique row per supplier_id
            ->groupBy('supplier_id')
            ->get();
        return $supplierByItem;
    }

    public function brandBySupplier($request)
    {

        $itemId = $request->item_id;
        $supplierId = $request->supplier_id;
        $supplierByItem = SupplierItem::with('brand', 'item', 'item_price.uom')
            ->where('item_id', $itemId)
            ->where('supplier_id', $supplierId)->get();
        return $supplierByItem;
    }

    public function itemImport($request)
    {

        $file = $request->file('item_import');
        $headings = (new HeadingRowImport)->toArray($file);
        $expectedHeadings = [
            'name',
            'code',
            'category_code',
            'item_type_code',
            'base_uom_code',
            'uom_code',
            'min_holding_base_uom_quantity',
            'min_holding_uom_quantity',
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $itemService = new ItemService();
        $import = new ItemsImport($itemService);
        $import->import($file);
        ResponseMessage('Import Successfully', 200);
    }

    public function brandlistOfSupplierByItem($itemId)
    {
        $brands = DB::table('supplier_items')
            ->join('brands', 'supplier_items.brand_id', '=', 'brands.id')
            ->where('supplier_items.item_id', $itemId)
            ->select('brands.id', 'brands.name') // Include only the necessary columns
            ->distinct() // Ensure unique rows
            ->get();
        // $supplierByItems = SupplierItem::with('supplier', 'item', 'brand')
        // ->where('item_id', $itemId)->get();
        // $brands = $supplierByItems->pluck('brand')->unique('id')->values();
        return $brands;
    }

    public function createCategory(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $category = Category::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            ResponseData($category);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    public function createItemType(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $itemType = ItemType::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            ResponseData($itemType);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function importItemType($request)
    {
        $file = $request->file('item_type_import');
        $headings = (new HeadingRowImport)->toArray($file);
        $expectedHeadings = [
            'item_type_code',
            'name',
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $import = new ItemTypeImport();
        $import->import($file);
        ResponseMessage('Import Type Import Successfully', 200);
    }

    public function importCategory($request)
    {
        $file = $request->file('category_import');
        $headings = (new HeadingRowImport)->toArray($file);
        $expectedHeadings = [
            'category_code',
            'name',
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $import = new CategoryImport();
        $import->import($file);
        ResponseMessage('Import Category Import Successfully', 200);
    }

    public function importUom($request)
    {
        $file = $request->file('uom_import');
        $headings = (new HeadingRowImport)->toArray($file);
        $expectedHeadings = [
            'uom_code',
            'name',
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $uom_import = new UomsImport();
        $uom_import->import($file);
        ResponseMessage('Import UOM Import Successfully', 200);
    }
}
