<?php
namespace App\Repositories\Report;
interface ReportInterface 
{
   public function getBarForSky($request);

   public function getTotalKTVCustomers($request);

   public function getWaiterSale($request);
}