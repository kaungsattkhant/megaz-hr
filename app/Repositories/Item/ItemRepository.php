<?php

namespace App\Repositories\Item;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemRepository implements ItemRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $category_id = $request->category_id;
            return Item::orderByDesc('id')
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search_input . '%');
                })
                ->when($category_id, function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                })
                ->paginate(config('common.list_count'));
        } else {
            if ($request->category_id) {
                return Item::where('category_id', $request->category_id)->get();
            }
            return Item::all();
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $item = Item::create($data);
            $uomIds = json_decode($data['uoms'], true);
            foreach ($uomIds as $uomId) {
                $item->uoms()->attach($uomId);
            }
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
