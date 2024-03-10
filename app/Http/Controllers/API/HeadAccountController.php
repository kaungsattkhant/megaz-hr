<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\HeadAccount\HeadAccountInterface;
use Illuminate\Http\Request;

class HeadAccountController extends Controller
{
    //
    private HeadAccountInterface $headAccountRepo;

    public function __construct(HeadAccountInterface $head_account_repo){
        $this->headAccountRepo=$head_account_repo;
    }
    public function store(Request $request){
        $itemUsageForecast=$this->headAccountRepo->updateOrCreate($request);
        ResponseData($itemUsageForecast);
   }

}
