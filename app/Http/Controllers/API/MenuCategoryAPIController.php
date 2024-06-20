<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\MenuCategory\MenuCategoryRepositoryInterface;
use Illuminate\Http\Request;

class MenuCategoryAPIController extends Controller
{
    //
    protected $menuCategoryRepo;
    public function __construct(MenuCategoryRepositoryInterface $menuCategoryRepo)
    {
        $this->menuCategoryRepo = $menuCategoryRepo;
    }

    public function getMenuCategories(Request $request)
    {
        $menuCategories = $this->menuCategoryRepo->listAllData($request);
    }

    public function createMenuCategory(Request $request)
    {
        $menuCategory = $this->menuCategoryRepo->createData($request->all());
    }

    public function editMenuCategory(int $id, Request $request)
    {
        $menuCategory = $this->menuCategoryRepo->editData($id,$request->all());
    }

    public function deleteMenuCategory(int $id)
    {
        $menuCategory = $this->menuCategoryRepo->deleteData($id);
    }

}
