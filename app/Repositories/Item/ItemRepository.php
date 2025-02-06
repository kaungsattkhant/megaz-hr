<?php

namespace App\Repositories\Item;

use Exception;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemPrice;
use App\Imports\ItemsImport;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use App\Models\UomConversion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\HeadingRowImport;
use App\Services\AveragePriceCalculator;
use Illuminate\Database\Eloquent\Builder;

class ItemRepository implements ItemRepositoryInterface
{
    protected $averagePriceCalculator;
    public function __construct(AveragePriceCalculator $repo)
    {
        $this->averagePriceCalculator = $repo;
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

            $minimumHoldingAmount = ($data['min_holding_base_uom_quantity'] * $conversionRate) + $data['min_holding_uom_quantity'];
            $data['minimum_holding_amount'] = $minimumHoldingAmount;

            $item = Item::create($data);

            DB::commit();
            return $item;
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

            if ($item) {
                if (isset($data['base_uom_id']) && isset($data['uom_id'])) {
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

                    if (isset($data['min_holding_base_uom_quantity']) && isset($data['min_holding_uom_quantity'])) {
                        $minimumHoldingAmount = ($data['min_holding_base_uom_quantity'] * $conversionRate) + $data['min_holding_uom_quantity'];
                        $data['minimum_holding_amount'] = $minimumHoldingAmount;
                    }
                }
                $item->update($data);
                if (isset($data['brands'])) {
                    $item->brands()->sync($data['brands']);
                }
            }
            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

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
        $supplierByItem = SupplierItem::with('brand', 'item', 'item_price')
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
            'category_id',
            'item_type_id',
            'base_uom_id',
            'uom_id',
            'lead_time',
            'minimum_holding_amount',
            'min_holding_base_uom_quantity',
            'min_holding_uom_quantity',
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $import = new ItemsImport();
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
}
