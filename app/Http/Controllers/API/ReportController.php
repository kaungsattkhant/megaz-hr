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
    public function getTotalKTVSessions(Request $request){
        $data=$this->reportRepo->getTotalKTVSessions($request);
        ResponseData($data);
    }

    public function getTotalKTVRoomCharges(Request $request){
        $data=$this->reportRepo->getTotalKTVRoomCharges($request);
        ResponseData($data);
    }

    public function getTotalKTVSales(Request $request){
        $data=$this->reportRepo->getTotalKTVSales($request);
        ResponseData($data);
    }

    public function getKTVTraining(Request $request){
        $data=$this->reportRepo->getKTVTraining($request);
        ResponseData($data);
    }

    public function getBarTotalExpense(Request $request){
        $data=$this->reportRepo->getBarTotalExpense($request);
        ResponseData($data);
    }

    public function getKitchenForSktyAndKtv(Request $request)
    {
        $data=$this->reportRepo->getKitchenForSktyAndKtv($request);
        ResponseData($data);
    }

    public function getKitchenTotalExpense(Request $request)
    {
        $data=$this->reportRepo->getKitchenTotalExpense($request);
        ResponseData($data);
    }

    public function getMonthlyKitchenMenuTotal(Request $request)
    {
        $data=$this->reportRepo->getMonthlyKitchenMenuTotal($request);
        ResponseData($data);
    }

    public function getDailyAreaSalesVolume(Request $request)
    {
        $data = $this->reportRepo->getDailyAreaSalesVolumeByStaff($request);
        ResponseData($data);
    }

    public function getMenuSales(Request $request)
    {
        $data = $this->reportRepo->getTargetActualMenuSales($request);
        ResponseData($data);
    }

    public function getBudgetAccountsCashflow(Request $request)
    {
        $data = $this->reportRepo->getBudgetAccountsCashflow($request);
        ResponseData($data);
    }
}
