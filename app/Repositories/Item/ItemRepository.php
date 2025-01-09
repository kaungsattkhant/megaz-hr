<?php

namespace App\Repositories\Item;

use Exception;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemPrice;
use App\Imports\ItemsImport;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
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
            $item = Item::create($data);
            // if (isset($data['brands'])) {
            //     $item->brands()->sync($data['brands']);
            // }
            // $price = ItemPrice::create(['item_id' => $item->id, 'price' => $data['price'],'uom_id'=>$data['uom_id']]); //removed after relationship with item price with supplier
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
                $item->update($data);
                // if (isset($data['uoms'])) {
                //     $item->uoms()->sync($data['uoms']);
                // }
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
            // $item_price = ItemPrice::where('item_id', $id)->latest('created_at')->first();
            // $new_item_price['uom_id'] = $item_price->uom_id;
            // $new_item_price['price'] = $data['price'];
            // $new_item_price['item_id'] = $id;
            // dd($data);
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
        $expectedHeadings = ['name', 'code', 'category_id', 'item_type_id', 'base_uom_id', 'uom_id'];
        $actualHeadings = $headings[0][0];
        if (array_slice($actualHeadings, 0, count($expectedHeadings)) != $expectedHeadings) {
            throw new Exception('Unexpected Headings', 400);
        }
        $import = new ItemsImport();
        $import->import($file);
        ResponseMessage('Import Successfully', 200);
    }
}
