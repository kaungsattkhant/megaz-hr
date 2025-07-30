<?php

namespace App\Repositories\SellingExtra;

use Illuminate\Http\Request;

use App\Models\SellingExtra;
use App\Models\SellingExtraCategory;

class SellingExtraRepository implements SellingExtraRepositoryInterface
{
    public function listAllCategories(Request $request)
    {
        $query = SellingExtraCategory::query()->with(['extras']);
        if ($request->per_page || $request->page) {
            return $query
            ->orderByDesc('id')
            ->paginate(config('common.list_count'));
        }else{
            return $query
            ->orderByDesc('id')
            ->get();
        }
    }

    public function createCategory(array $data)
    {
        $category = SellingExtraCategory::create($data);
        return $category;
    }

    public function listAllData(Request $request, bool $isPosQuerying = false)
    {
        $query = SellingExtra::query()->with(['category','item.uom','item.base_uom','uom']);
        if($request->category_id){
            $query->where('selling_extra_category_id',$request->category_id);
        }
        if($isPosQuerying){
            $query->where('is_active',1);
        }
        if ($request->per_page || $request->page) {
            return $query
            ->orderByDesc('id')
            ->paginate(config('common.list_count'));
        }else{
            return $query
            ->orderByDesc('id')
            ->get();
        }
    }

    public function find($id)
    {
        return SellingExtra::with(['category','item.uom','item.base_uom','uom'])->findOrFail($id);
    }

    public function create(array $data)
    {
        $selling = SellingExtra::create($data);
        return SellingExtra::with(['category','item.uom','item.base_uom','uom'])->findOrFail($selling->id);
    }

    public function update(array $data, $id)
    {
        $selling = SellingExtra::findOrFail($id);
        $selling->update($data);
        return SellingExtra::with(['category','item.uom','item.base_uom','uom'])->findOrFail($id);
    }

    public function delete($id)
    {
        return SellingExtra::destroy($id);
    }
}
