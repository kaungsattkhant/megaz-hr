<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Menu\AddPriceToMenuRequest;
use App\Http\Requests\Menu\CreateMenuRequest;

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

    public function createMenu(CreateMenuRequest $request)
    {
        $data = $request->except('items');
        $items = json_decode($request->items, true)['items'];
        $menu = $this->menuRepo->createData($data, $items);

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
}
