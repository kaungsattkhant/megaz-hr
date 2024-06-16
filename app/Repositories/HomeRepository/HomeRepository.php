<?php

namespace App\Repositories\HomeRepository;

use App\Models\Menu;
use App\Models\MenuCategory;

class HomeRepository implements HomeInterface
{
    public function getHomeCategoryList(){
        return MenuCategory::orderBy('name','asc')
        ->take(12)
        ->get();
    }

    public function getHomeMenuList($request){
        return Menu::with(['price'])->paginate(20);
    }
}
