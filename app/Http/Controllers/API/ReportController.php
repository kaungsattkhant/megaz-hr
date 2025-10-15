<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Report\ReportInterface;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    private $reportRepo;
    public function __construct(ReportInterface $repo){
        $this->reportRepo=$repo;
    }
    public function getBarForSky(Request $request){
        $data=$this->reportRepo->getBarForSky($request);
        ResponseData($data);
    }

    public function getTotalKTVCustomers(Request $request){
        $data=$this->reportRepo->getTotalKTVCustomers($request);
        ResponseData($data);
    }
    public function getWaiterSale(Request $request){
         $data=$this->reportRepo->getWaiterSale($request);
        ResponseData($data);
    }
}
