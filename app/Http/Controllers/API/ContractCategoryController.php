<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContractCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContractCategory::orderBy('id', 'DESC');

        $categories = $request->has('per_page') || $request->has('page')
            ? $query->paginate(config('common.list_count'))
            : $query->get();

        ResponseData($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $data = $request->all();
        // return DB::beginTransaction(function() use ($data){
            $category = ContractCategory::updateOrCreate(
                ['id' => $data['id'] ?? null],
                $data
            );
            return $category;
        // });
    }
}
