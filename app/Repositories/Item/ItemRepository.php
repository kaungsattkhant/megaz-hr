<?php

namespace App\Repositories\Item;

use Exception;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemType;
use App\Models\ItemPrice;
use App\Imports\UomsImport;
use App\Models\ArrivalItem;
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
use App\Repositories\PoOrder\PoOrderRepository;

class ItemRepository implements ItemRepositoryInterface
{
    protected $averagePriceCalculator;
    protected $itemService;
    protected $poOrderRepository;
    public function __construct(AveragePriceCalculator $repo, ItemService $itemService, PoOrderRepository $poOrderRepository)
    {
        $this->averagePriceCalculator = $repo;
        $this->itemService = $itemService;
        $this->poOrderRepository = $poOrderRepository;
    }

    public function listAllData(Request $request)
    {
        $category_id = $request->category_id;
        $searchInput = $request->search_input;
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
                ->when($searchInput, function ($q) use ($searchInput) {
                    $q->where('items.name', 'LIKE', '%' . $searchInput . '%');
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
                ->when($searchInput, function ($q) use ($searchInput) {
                    $q->where('items.name', 'LIKE', '%' . $searchInput . '%');
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

            if (!isset($data['id'])) {
                $data['id'] = null;
            }
            if (!isset($data['base_uom_id']) || !isset($data['uom_id'])) {
                return ResponseMessage('Required UOM data is missing.', 400);
            }
            if (!isset($data['min_holding_quantity']) && !isset($data['min_holding_uom_quantity'])) {
                return ResponseMessage('Required holding quantities are missing.', 400);
            }
            $isBaseUomChange = false;
            $isUomChange = false;
            $isConversionChange = false;
            if (isset($data['id'])) {
                $item = Item::find($data['id']);
                if ($item->base_uom_id != $data['base_uom_id']) {
                    $isBaseUomChange = true;
                    $baseUomId = $item->base_uom_id;
                }
                if ($item->uom_id != $data['uom_id']) {
                    $isUomChange = true;
                    $uomId = $item->uom_id;
                }
                // if ($item->conversion != $data['conversion']) {
                //     $isConversionChange = true;
                // }
            }
            $brand_suppliers = json_decode($data['brand_suppliers'], true);

            $conversionRate = $data['conversion'];
            $minimumHoldingAmount = $this->itemService->calculateMinimumHoldingAmount(
                $data['min_holding_quantity'] ?? 0,
                $data['min_holding_uom_quantity'] ?? 0,
                $conversionRate
            );
            $data['minimum_holding_amount'] = $minimumHoldingAmount;

            if (!isset($data['limitation_type']) || !in_array($data['limitation_type'], ["uom", "finance"])) {
                return ResponseMessage('Invalid limitation_type. It must be either "uom" or "finance".', 422);
            }

            if ($data['limitation_type'] === "uom") {
                $hasBaseUom = isset($data['max_limit_quantity']);
                $hasUom = isset($data['max_limit_uom_quantity']);
                if (!$hasBaseUom && !$hasUom) {
                    return ResponseMessage('For limitation_type "uom", provide either max_limit_quantity or max_limit_uom_quantity.', 422);
                }

                if($hasBaseUom && !$hasUom){
                    $data['max_limit_uom_quantity'] = ($data['max_limit_quantity'] ?? 0) *  $conversionRate;
                }
            }

            if ($data['limitation_type'] === "finance") {
                if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
                    return ResponseMessage('For limitation_type "finance", amount must be a positive number.', 422);
                }
            }

            if(isset($data['min_holding_quantity']) && isset($data['base_uom_id'])){
                $data['min_holding_uom_quantity'] = ($data['min_holding_quantity'] ?? 0) *  $conversionRate;
            }

            $item = Item::updateOrCreate(
                ['items.id' => $data['id']],
                $data
            );
            // $item = Item::firstOrCreate(['items.name' => $data['name'], 'items.code' => $data['code']], $data);
            // if (isset($data['brand_id'])) {
            //     $item->brands()->sync($data['brand_id']);
            // }
            //create uom converion 
            $uomConversion = $this->createUomConversion($item, $data);
            if (!empty($brand_suppliers)) {
                foreach ($brand_suppliers as $bs) {
                    // $bs['id'] = 10; //testing
                    $bs['item_id'] = $item->id;
                    if (!isset($bs['id'])) {
                        $bs['id'] = null;
                    }

                    //check edit when uom change 
                    if (($isBaseUomChange && $bs['uom_id'] == $baseUomId) || ($isUomChange && $bs['uom_id'] == $uomId)) {
                        ResponseMessage('Need to update uom_price for uom changes', 419);
                    }
                    //end
                    if ($bs['uom_id'] == $data['base_uom_id'] && $bs['uom_id'] != $data['uom_id']) {
                        $bs['type'] = 'base_uom';
                        $bs['price'] = $bs['uom_price'] ;
                    } elseif ($bs['uom_id'] == $data['uom_id'] && $bs['uom_id'] != $data['base_uom_id']) {
                        $bs['type'] = 'uom';
                        $bs['price'] = $data['conversion'] * $bs['uom_price'];
                    } elseif ($bs['uom_id'] == $data['uom_id'] && $bs['uom_id'] == $data['base_uom_id']) {
                        $bs['type'] = 'uom';
                        $bs['price'] = $data['conversion'] * $bs['uom_price'];
                    } else {
                        ResponseMessage($bs['type'] . ' is missing for item_pirce', 419);
                    }
                    $supplierItem = SupplierItem::updateOrCreate(['id' => $bs['id']], $bs);
                    //change item price
                    $isItemPriceChange = false;
                    // dd((double)$supplierItem->item_price->price != (double) $bs['price']);
                    if ($supplierItem->item_price && ($supplierItem->item_price->price != ( $bs['price']))) {
                        $isItemPriceChange = true;
                    }
                    //end
                    // if(!isset($bs['id'])){
                    //     dd('Uom change' , $isUomChange ,'Base Change',$isBaseUomChange,'Conversion change',$isConversionChange,'Item Price Change',$isItemPriceChange);
                    // }
                    if (!isset($bs['id']) || $isUomChange || $isBaseUomChange || $isConversionChange || $isItemPriceChange) {
                        $createdItemPrice = ItemPrice::create([
                            'supplier_item_id' => $supplierItem->id,
                            'uom_id' => $bs['uom_id'],
                            'type' => $bs['type'],
                            'uom_price' => $bs['uom_price'],
                            'price' => $bs['price'],
                        ]);
                    }
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

    public function checkUom($data)
    {
        if (isset($data['id'])) {
            $item = Item::find($data['id']);
            if ($item->base_uom_id != $data['base_uom_id']) {
                dd('base_uom is diff');
            }
            if ($item->uom_id != $data['uom_id']) {
                dd('base_uom is diff');
            }
        }

        dd('correct');
    }
    public function detail($id)
    {
        $item = Item::with(['uom','tag', 'base_uom', 'supplier_item.item_price.uom', 'supplier_item.brand', 'supplier_item.supplier'])->find($id);
        if (!$item) {
            ResponseMessage('Item not found', 419);
        }
        return $item;
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
                if (!isset($data['limitation_type']) || !in_array($data['limitation_type'], ['uom', 'finance'])) {
                    return ResponseMessage('Invalid limitation_type. It must be either "uom" or "finance".', 422);
                }

                if ($data['limitation_type'] === "uom") {
                    if (!isset($data['max_limit_base_uom_quantity']) || !is_numeric($data['max_limit_base_uom_quantity']) || $data['max_limit_base_uom_quantity'] <= 0) {
                        return ResponseMessage('For limitation_type "uom", max_limit_base_uom_quantity must be a positive number.', 422);
                    }
                    if (!isset($data['max_limit_uom_quantity']) || !is_numeric($data['max_limit_uom_quantity']) || $data['max_limit_uom_quantity'] <= 0) {
                        return ResponseMessage('For limitation_type "uom", max_limit_uom_quantity must be a positive number.', 422);
                    }
                }

                if ($data['limitation_type'] === "finance") {
                    if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
                        return ResponseMessage('For limitation_type "finance", amount must be a positive number.', 422);
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
            if ((isset($data['type']) && $data['type'] === 'uom')) {
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
        // $supplierByItem = SupplierItem::join('items', 'supplier_items.item_id', '=', 'items.id')
        //     ->join('suppliers', 'supplier_items.supplier_id', '=', 'suppliers.id') // optional if you need supplier data
        //     ->join('brands', 'supplier_items.brand_id', '=', 'brands.id')         // optional if you need brand data
        //     ->where('supplier_items.item_id', $itemId)
        //     ->select(
        //         'supplier_items.supplier_id',
        //         'items.name as item_name',
        //         'suppliers.name as supplier_name',
        //         DB::raw('MAX(supplier_items.id) as id')
        //     )
        //     ->groupBy('supplier_items.supplier_id', 'items.name')
        //     ->get();
        $supplierByItem = SupplierItem::with('supplier', 'brand')
            ->join('items', 'supplier_items.item_id', 'items.id')
            ->where('items.id', $itemId)
            ->select('supplier_id', 'items.name as item_name', DB::raw('MAX(supplier_items.id) as id')) // Use MAX(id) to pick a unique row per supplier_id
            ->groupBy('supplier_id')
            ->get();
        //         foreach($supplierByItem as $supplier){
//   // Calculate Average Quality
//             $averageQuality = ArrivalItem::where('item_id', $itemId)
//                 ->where('supplier_id', $supplier->supplier_id)
//                 ->avg('quality');
//             $supplier->average_quality = $averageQuality ? round($averageQuality, 2) : null;
//             // Get Lead Time
//             $leadTimeData = $this->poOrderRepository->getSupplierLeadTime($supplier->supplier_id);
//             $leadTime = null;
//             if (isset($leadTimeData['details'])) {
//                 foreach ($leadTimeData['details'] as $detail) {
//                     if ($detail['item_id'] == $itemId) {
//                         $leadTime = $detail['average_order_time'];
//                         break;
//                     }
//                 }
//             }
//             $supplier->lead_time = $leadTime;
//         }
        return $supplierByItem;
    }

    public function brandBySupplier($request)
    {
        $itemId = $request->item_id;
        $supplierId = $request->supplier_id;
        $supplierByItem = SupplierItem::with('brand', 'supplier', 'item', 'item_price.uom')
            ->where('item_id', $itemId)
            ->where('supplier_id', $supplierId)->get();
        // dd($supplierByItem);
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
            'limitation_type',
            'amount',
            'max_limit_base_uom_quantity',
            'max_limit_uom_quantity',
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

    public function createUomConversion($item, $data)
    {
        // if (isset($data['id'])) {
        //     // dd($item);
        //     $existConversion = UomConversion::where('item_id', $item->id)
        //         ->orderBy('id', 'desc')
        //         ->first();
        //     if (!$existConversion) {
        //         $uomConversion = UomConversion::create([
        //             'item_id' => $item->id,
        //             'base_unit_id' => $data['base_uom_id'],
        //             'conversion_unit_id' => $data['uom_id'],
        //             'conversion' => $data['conversion'],
        //         ]);
        //         return $uomConversion;
        //     }
        //     if ($data['base_uom_id'] != $existConversion->base_unit_id || $data['uom_id'] != $existConversion->conversion_unit_id) {
        //         $uomConversion = UomConversion::create([
        //             'item_id' => $item->id,
        //             'base_unit_id' => $data['base_uom_id'],
        //             'conversion_unit_id' => $data['uom_id'],
        //             'conversion' => $data['conversion'],
        //         ]);
        //         return $uomConversion;
        //     }
        // } else {
        //     $uomConversion = UomConversion::create([
        //         'item_id' => $item->id,
        //         'base_unit_id' => $data['base_uom_id'],
        //         'conversion_unit_id' => $data['uom_id'],
        //         'conversion' => $data['conversion'],
        //     ]);
        //     return $uomConversion;
        // }

        $shouldCreate = false;

        if (isset($data['id'])) {
            $existConversion = UomConversion::where('item_id', $item->id)
                ->orderBy('id', 'desc')
                ->first();
            if (
                !$existConversion ||
                $data['base_uom_id'] != $existConversion->base_unit_id ||
                $data['uom_id'] != $existConversion->conversion_unit_id ||
                $data['conversion'] != $existConversion->conversion
            ) {
                $shouldCreate = true;
            }

        } else {
            $shouldCreate = true;
        }
        if ($shouldCreate) {
            return UomConversion::create([
                'item_id' => $item->id,
                'base_unit_id' => $data['base_uom_id'],
                'conversion_unit_id' => $data['uom_id'],
                'conversion' => $data['conversion'],
            ]);
        }

        return null;

    }
}
