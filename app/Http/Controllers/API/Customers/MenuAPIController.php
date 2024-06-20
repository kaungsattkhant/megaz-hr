<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Repositories\Menu\MenuRepository;
use App\Repositories\Menu\MenuRepositoryInterface;
use Illuminate\Http\Request;

class MenuAPIController extends Controller
{
    //
    protected $menuRepo;
    public function __construct(MenuRepositoryInterface $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function listMenuData(Request $request)
    {
        $menu = $this->menuRepo->listAllMenu($request);
        Responsedata($menu);
    }

    public function categoryMenuByUserApp(int $id)
    {
        $menu = Menu::where('menu_category_id',$id)->with('prices')->get();
        ResponseData($menu);
    }
}
