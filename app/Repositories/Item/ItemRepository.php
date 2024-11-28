<?php

namespace App\Repositories\Item;

use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemPrice;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ItemRepository implements ItemRepositoryInterface
{
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
                // ->where('items.category_id', $request->category_id)
                ->select('items.*', DB::raw('
                                            CAST((
                                                SELECT COALESCE(AVG(latest_prices.price), 0) 
                                                FROM (
                                                    SELECT ip.price 
                                                    FROM supplier_items si
                                                    JOIN item_prices ip ON si.id = ip.supplier_item_id
                                                    WHERE si.item_id = items.id
                                                    AND ip.id = (
                                                        SELECT MAX(sub_ip.id)
                                                        FROM item_prices sub_ip
                                                        WHERE sub_ip.supplier_item_id = si.id
                                                    )
                                                ) AS latest_prices
                                            ) AS DECIMAL(10,2)) AS average_price
                                        '))
                ->orderByDesc('id')
                ->paginate(config('common.list_count'));
        } else {
            // if ($request->category_id) {
            //     return Item::with('category')
            //     ->where('category_id', $request->category_id)
            //     ->get();
            // }
            // $items= Item::with('category')->get();
            // return $items;
            return Item::with([
                'category',
                'supplier_items.item_price' => function ($query) {
                    $query->orderByDesc('id');
                }
            ])
                ->when((isset($request->category_id) && $category_id), function ($q) use ($category_id) {
                    $q->where('items.category_id', $category_id);
                })
                // ->where('items.category_id', $request->category_id)
                ->select('items.*', DB::raw('
            CAST((
                SELECT COALESCE(AVG(latest_prices.price), 0) 
                FROM (
                    SELECT ip.price 
                    FROM supplier_items si
                    JOIN item_prices ip ON si.id = ip.supplier_item_id
                    WHERE si.item_id = items.id
                    AND ip.id = (
                        SELECT MAX(sub_ip.id)
                        FROM item_prices sub_ip
                        WHERE sub_ip.supplier_item_id = si.id
                    )
                ) AS latest_prices
            ) AS DECIMAL(10,2)) AS average_price
        '))
                ->orderByDesc('id')
                ->get();
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $item = Item::create($data);
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

                if (isset($data['uoms'])) {
                    $item->uoms()->sync($data['uoms']);
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
        $supplierByItem = SupplierItem::with('supplier', 'item', 'item_price')
            ->where('item_id', $itemId)->get();
        return $supplierByItem;
    }

}
