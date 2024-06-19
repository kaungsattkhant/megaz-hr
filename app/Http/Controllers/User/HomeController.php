<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\HomeRepository\HomeInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $homeRepo;
    public function __construct(HomeInterface $repo)
    {
        $this->homeRepo=$repo;
    }

    public function getHomeCategoryList(){
        $data=$this->homeRepo->getHomeCategoryList();
        ResponseData($data);
    }

    public function getHomeMenuList(Request $request){
        $data=$this->homeRepo->getHomeMenuList($request);
        ResponseData($data);
    }
}
