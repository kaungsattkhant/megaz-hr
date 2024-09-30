<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Menu\AddPriceToMenuRequest;
use App\Http\Requests\Menu\CreateMenuRequest;
use App\Models\Menu;
use App\Repositories\Menu\MenuRepositoryInterface;

class MenuAPIController extends Controller
{
    private $menuRepo;

    public function __construct(MenuRepositoryInterface $repo)
    {
        $this->menuRepo = $repo;
    }

    public function getMenus(Request $request)
    {
        $menus = $this->menuRepo->listAllData($request);
        ResponseData($menus);
    }

    public function createMenu(Request $request)
    {
        $data = $request->except('items');
        $items = json_decode($request->items, true)['items'];
        $areas = json_decode($request->areas, true);
        $menu = $this->menuRepo->createData($data,$items,$areas);
        ResponseData($menu);
    }

    public function addPriceToMenu(AddPriceToMenuRequest $request, int $id)
    {
        $menuPrice = $this->menuRepo->createMenuPrice($id, $request->price);
        if(!$menuPrice){
            ResponseMessage('No menu found with given id', 404);
        }

        ResponseData($menuPrice);
    }

    public function menuByMenuCategory(int $id)
    {
        $menu = Menu::where('menu_category_id',$id)->with('prices','menuServiceDiscounts')->get();
        // $menu = Menu::where('menu_category_id',$id)->with('prices')->get();
        ResponseData($menu);
    }

    public function menuByMenuCategoryBooking(int $id,Request $request)
    {
        $validateDate = $request->date ?? CurrentDate();
        $menu = Menu::where('menu_category_id',$id)->where('is_feature',1)->with(['menu_category', 'prices', 'items','menuServiceDiscounts' => function ($query) use ($validateDate)
        {
            $query->where('from_date', '<=',$validateDate)->where('to_date', '>=',$validateDate);
        }
        ])->paginate(config('common.list_count'));
        ResponseData($menu);
    }


    public function menuOnOff(int $id)
    {
        $menu = $this->menuRepo->menuIsActive($id);
    }

    public function detailMenu(int $id)
    {
        $menu = $this->menuRepo->menuDetail($id);
        ResponseData($menu);
    }

    public function menuEdit(Request $request,int $id)
    {
        $data = $request->except('items');
        $items = json_decode($request->items, true)['items'];
        $areas = json_decode($request->areas, true);
        $menu = $this->menuRepo->editMenu($id, $data, $items,$areas);
        ResponseData($menu);
    }

    public function featureToggleMenu(int $id)
    {
        $menus = $this->menuRepo->toggleMenuFeature($id);
        ResponseData($menus);
    }

    public function areaByMenu(int $id)
    {
        $areas = $this->menuRepo->menuAreaList($id);
    }
}
