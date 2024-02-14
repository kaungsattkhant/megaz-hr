<?php

namespace App\Repositories\Item;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemRepository implements ItemRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allItem = Item::all();
        $items = Pagination($allItem, $request, 'items');
        return $items;
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
