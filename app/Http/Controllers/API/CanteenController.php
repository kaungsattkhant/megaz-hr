<?php

namespace App\Http\Controllers\API;

use App\Models\Canteen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Canteen\CanteenInterface;

class CanteenController extends Controller
{
    //
    protected $canteenRepo;
    public function __construct(CanteenInterface $canteenRepo)
    {
        $this->canteenRepo = $canteenRepo;
    }
    public function index(Request $request){
        $data=$this->canteenRepo->list($request);
        ResponseData($data);
    }

    public function store(Request $request){
        $data=$this->canteenRepo->store($request);
        ResponseData($data);
    }

    public function show(Canteen $canteen){
        
    }
}
