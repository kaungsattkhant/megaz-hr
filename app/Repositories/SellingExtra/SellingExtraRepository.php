<?php

namespace App\Repositories\SellingExtra;

use Illuminate\Http\Request;

use App\Models\SellingExtra;

class SellingExtraRepository implements SellingExtraRepositoryInterface
{
    public function listAllData(Request $request, bool $isPosQuerying = false)
    {
        $query = SellingExtra::query()->with(['item.uom','item.base_uom','uom']);
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
        return SellingExtra::with(['item.uom','item.base_uom','uom'])->findOrFail($id);
    }

    public function create(array $data)
    {
        $selling = SellingExtra::create($data);
        return SellingExtra::with(['item.uom','item.base_uom','uom'])->findOrFail($selling->id);
    }

    public function update(array $data, $id)
    {
        $selling = SellingExtra::findOrFail($id);
        $selling->update($data);
        return SellingExtra::with(['item.uom','item.base_uom','uom'])->findOrFail($id);
    }

    public function delete($id)
    {
        return SellingExtra::destroy($id);
    }
}
