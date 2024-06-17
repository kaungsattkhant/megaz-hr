<?php

namespace App\Repositories\Menu;

use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\MenuPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuRepository implements MenuRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $menu_category_id = $request->menu_category_id;
            return Menu::with(['menu_category', 'prices', 'items'])
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search_input . '%');
                })
                ->when($menu_category_id, function ($query) use ($menu_category_id) {
                    $query->where('menu_category_id', $menu_category_id);
                })
                ->paginate(config('common.list_count'));
        } else {
            $menus = Menu::with(['menu_category', 'prices', 'items'])->where('is_active', 1)->get();
            return $menus;
        }
    }

    public function createData(array $data, array $items)
    {
        DB::beginTransaction();
        try {
            $imageData = $data['image'];
            $extension = $imageData->getClientOriginalExtension();
            $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
            $data['image_path'] = $imageData->storeAs('images/menu_images', $hashedName, 'public');
            $data['image_url'] = Storage::url($data['image_path']);
            $menu = Menu::create($data);
            $this->createMenuPrice($menu->id, $data['price']);
            foreach ($items as $item) {
                $menu->items()->attach($item['id'], [
                    'uom_id' => $item['uom_id'],
                    'weight' => $item['weight'],
                    'price' => $item['price'],
                    'is_make_pack' => $item['is_make_pack'] ? 1 : 0
                ]);
            }
            DB::commit();
            return $menu;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createMenuPrice(int $id, float $price)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $menuPrice = MenuPrice::create([
                "menu_id" => $menu->id,
                "price" => $price
            ]);

            return $menuPrice;
        }

        return null;
    }

    public function menuDetail(int $id)
    {
        // $menu = Menu::find($id)->with('items.uoms', 'prices', 'menu_category')->first();
        $menu = Menu::with('items.uoms', 'prices', 'menu_category')->find($id);
        return $menu;
    }

    public function menuIsActive(int $id)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::find($id);
            $menu->is_active = !$menu->is_active;
            $menu->save();
            DB::commit();
            ResponseMessage('Menu is active status has been changed');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function editMenu(int $id, array $data, array $items)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);

            if (isset($data['image'])) {
                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('images', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);
            }

            $menu->update($data);

            if (isset($data['price'])) {
                $this->updateMenuPrice($menu->id, $data['price']);
            }
            if (isset($items)) {
                // $menu->items()->detach();
                $syncData = [];
                foreach ($items as $item) {
                    if($menu->items()->where('item_id', $item['id'])->where('uom_id', $item['uom_id'])->first()){
                        $menu->items()->detach($item['id']);
                    }
                    $syncData[$item['id']] = [
                        'uom_id' => $item['uom_id'],
                        'weight' => $item['weight'],
                        'price' => $item['price'],
                        'is_make_pack' => $item['is_make_pack'] ? 1 : 0
                    ];

                    $menu->items()->attach($item['id'], [
                        'uom_id' => $item['uom_id'],
                        'weight' => $item['weight'],
                        'price' => $item['price'],
                        'is_make_pack' => $item['is_make_pack'] ? 1 : 0
                    ]);
                }
                // $menu->items()->sync($syncData);
            }

            DB::commit();
            return $menu;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateMenuPrice(int $id, float $price)
    {
        $menuPrice = MenuPrice::where('menu_id', $id)->latest()->first();
        if ($menuPrice) {
            if ($menuPrice->price != $price) {
                MenuPrice::create([
                    'menu_id' => $id,
                    'price' => $price
                ]);
            }
        }
    }
}
