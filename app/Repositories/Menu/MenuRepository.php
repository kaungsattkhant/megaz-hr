<?php

namespace App\Repositories\Menu;

use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\MenuPrice;

class MenuRepository implements MenuRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Menu::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $menus = Menu::with(['category', 'prices', 'items'])
            ->where('is_active', 1)
            ->skip($skip)->take($perPage)
            ->get();

            $menus = MakePaginationData($request, $totalCount, 'menus', $menus);

            return $menus;
        }
        else{
            $menus = Menu::with(['category', 'prices', 'items'])->where('is_active', 1)->get();

            return $menus;
        }
    }

    public function createData(array $data, array $items)
    {
        $menu = Menu::create($data);
        $this->createMenuPrice($menu->id, $data['price']);
        foreach($items as $item){
            $menu->items()->attach($item['id'], [
                'weight' => $item['weight'],
                'is_make_pack' => $item['is_make_pack']? 1:0
            ]);
        }

        return $menu;
    }

    public function createMenuPrice(int $id, float $price)
    {
        $menu = Menu::find($id);
        if($menu){
            $menuPrice = MenuPrice::create([
                "menu_id" => $menu->id,
                "price" => $price
            ]);

            return $menuPrice;
        }

        return null;
    }
}
