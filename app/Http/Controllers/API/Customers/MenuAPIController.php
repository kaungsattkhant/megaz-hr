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


    public function categoryMenuByUserApp(int $id,Request $request)
    {
        $validateDate = $request->date ?? CurrentDate();
        $menu = Menu::where('menu_category_id',$id)->where('is_feature',1)->with(['menu_category', 'prices', 'items','menuServiceDiscounts' => function ($query) use ($validateDate)
        {
            $query->where('from_date', '<=',$validateDate)->where('to_date', '>=',$validateDate);
        }
        ])->paginate(config('common.list_count'));
        ResponseData($menu);
    }
}
