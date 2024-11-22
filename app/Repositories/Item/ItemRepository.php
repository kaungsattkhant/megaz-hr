<?php

namespace App\Repositories\Item;

use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemPrice;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemRepository implements ItemRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $category_id = $request->category_id;
            return Item::with(['category'])->orderByDesc('id')
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search_input . '%');
                })
                ->when($category_id, function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                })
                ->paginate(config('common.list_count'));
        } else {
            if ($request->category_id) {
                return Item::with('category')->where('category_id', $request->category_id)->get();
            }
            return Item::with('category')->get();
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
        $data=$request->all();
        DB::beginTransaction();
        try {
            // $item_price = ItemPrice::where('item_id', $id)->latest('created_at')->first();
            // $new_item_price['uom_id'] = $item_price->uom_id;
            // $new_item_price['price'] = $data['price'];
            // $new_item_price['item_id'] = $id;
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

    public function getItemPriceListByItem($item_id){
        return ItemPrice::with('uom')->orderBy('id','desc')->where('item_id',$item_id)->paginate(20);
    }

    public function getItemType(){
        return ItemType::all();
    }

    public function supplierByItem($itemId){
        $supplierByItem=SupplierItem::with('supplier','item','item_price')
        ->where('item_id',$itemId)->get();
        return $supplierByItem;
    }

}
