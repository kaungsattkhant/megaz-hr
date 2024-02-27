<?php

namespace App\Repositories\Item;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemRepository implements ItemRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Item::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $items = Item::skip($skip)->take($perPage)->get();
            $itemsData = MakePaginationData($request, $totalCount, 'items', $items);

            return $itemsData;
        }
        else{
            if($request->category_id){
                $items = Item::where('category_id', $request->category_id)->get();
            }
            else{
                $items = Item::all();
            }
            return $items;
        }
    }

    public function createData(array $data)
    {
        $item = Item::create($data);
        foreach ($data['uoms'] as $uomId) {
            $item->uoms()->attach($uomId);
        }
        return $item;
    }

    public function updateData(array $data, int $id)
    {
        $item = Item::find($id);
        if ($item) {
            $item->update($data);

            if(isset($data['uoms']))
            {
                $item->uoms()->sync($data['uoms']);
            }
        }
        return $item;
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
}
