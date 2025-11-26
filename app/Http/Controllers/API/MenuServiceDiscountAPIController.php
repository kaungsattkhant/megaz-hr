<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\MenuServiceDiscount\MenuServiceDiscountRepositoryInterface;
use Illuminate\Http\Request;

class MenuServiceDiscountAPIController extends Controller
{
    //
    protected $msdRepo;
    public function __construct(MenuServiceDiscountRepositoryInterface $msdRepo)
    {
        $this->msdRepo = $msdRepo;
    }

    public function getMenuServiceDiscountData(Request $request)
    {
        $msd = $this->msdRepo->listAllData($request);
    }

    public function createMenuServiceDiscount(Request $request)
    {
        $msd = $this->msdRepo->createData($request->all());
    }

    public function editMenuServiceDiscount(int $id, Request $request)
    {
        $msd = $this->msdRepo->editData($id,$request->all());
    }

    public function deleteMenuServiceDiscount(int $id)
    {
        $msd = $this->msdRepo->deleteData($id);
    }
}
