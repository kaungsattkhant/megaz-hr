<?php

namespace App\Repositories\MenuCategory;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuCategoryRepository implements MenuCategoryRepositoryInterface
{
    public function listAllData(Request $request)
    {

        $menuCategories = MenuCategory::orderBy('created_at','desc')->when($request->search, function($q) use ($request)
        {
            $q->where('name','LIKE','%'.$request->search.'%');
        });

        $menuCategories = $request->has('page')
        ? $menuCategories->paginate(config('common.list_count'))
        : $menuCategories->get();

        ResponseData($menuCategories);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            if ($data['image']) {
                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('images/menu_category_images', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);
            }
            $menuCategory = MenuCategory::create($data);
            DB::commit();
            ResponseData($menuCategory);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function editData(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['image'])) {
                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('images/menu_category_images', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);
            }
            $menuCategory = MenuCategory::find($id);
            $menuCategory->update($data);
            DB::commit();
            ResponseData($menuCategory);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        DB::beginTransaction();
        try {
            $menuCategory = MenuCategory::find($id);
            $menuCategory->delete();
            DB::commit();
            ResponseMessage('Menu Category deleted');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }


    // user app

    public function listAllDataUser()
    {
        ResponseData(MenuCategory::where('is_active', 1)->orderBy('created_at','desc')->get());
    }
}
