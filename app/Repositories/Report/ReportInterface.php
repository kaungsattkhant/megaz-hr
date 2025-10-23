<?php
namespace App\Repositories\Report;
interface ReportInterface
{
   public function getBarForSky($request);

   public function getTotalKTVCustomers($request);

   public function getWaiterSale($request);
   public function getTotalKTVSessions($request);

   public function getTotalKTVRoomCharges($request);

   public function getTotalKTVSales($request);

   public function getKTVTraining($request);

   public function getBarTotalExpense($request);

   public function getTargetActualMenuSales($request);

   public function getDailyAreaSalesVolumeByStaff($request);
}
