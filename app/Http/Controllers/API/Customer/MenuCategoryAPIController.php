<?php

namespace App\Http\Controllers\API\Customer;

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

    public function getMenuCategoriesbyUserApp()
    {
        $menuCategories = $this->menuCategoryRepo->listAllDataUser();
    }
}
