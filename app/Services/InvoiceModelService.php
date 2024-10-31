<?php

namespace App\Services;

use App\Models\Entity;
use Illuminate\Support\Carbon;



class InvoiceModelService
{
    public function updateEntityStatus($entityId, $status)
    {
        return Entity::where('id', $entityId)->update([
            'is_active' => $status == 'inactive' ? 0 : 1,
            'status' => $status,
        ]);
    }

    public function calculateInvoiceService($invoiceService, $end_time)
    {
        // foreach ($invoiceServices as $invoiceService) {
        $startDateTime = Carbon::parse($invoiceService->start_date);
        $currentDateTime = Carbon::parse(now());
        $pricePerHour = $invoiceService->service->price_per_hour;
        $hours = $startDateTime->diffInHours($currentDateTime);
        $minuteDifference = ceil($startDateTime->diffInMinutes($currentDateTime));
        $hoursDifference = $startDateTime->diffInMinutes($currentDateTime) / 60;
        // Calculate the total price with decimal places
        $serviceAmount = $hoursDifference * $pricePerHour;
        if ($hours < 1) {
            $formattedTimeDifference = "{$minuteDifference}min";
        } else {
            $hours = floor($hoursDifference);
            $minutes = round(($hoursDifference - $hours) * 60);
            $formattedTimeDifference = "{$hours}hr {$minutes}min";
            // $formattedTimeDifference = "{$hours}hr {$minuteDifference}min";
        }
        $invoiceService->service_value = ceil($serviceAmount);
        $invoiceService->minutes = $formattedTimeDifference;
        $invoiceService->price_per_hour = $pricePerHour;
        return $invoiceService;
        // }
    }

    public function getServiceValue($invoiceService, $end_time)
    {
        if(!$invoiceService->is_active){
            return $invoiceService->service_value;
        }
        $startDateTime = Carbon::parse($invoiceService->start_date);
        $currentDateTime = Carbon::parse(now());
        $pricePerHour = $invoiceService->service->price_per_hour;
        $hours = $startDateTime->diffInHours($currentDateTime);
        $minuteDifference = ceil($startDateTime->diffInMinutes($currentDateTime));
        $hoursDifference = $startDateTime->diffInMinutes($currentDateTime) / 60;
        // Calculate the total price with decimal places
        $serviceAmount = $hoursDifference * $pricePerHour;
        if ($hours < 1) {
            $formattedTimeDifference = "{$minuteDifference}min";
        } else {
            $hours = floor($hoursDifference);
            $minutes = round(($hoursDifference - $hours) * 60);
            $formattedTimeDifference = "{$hours}hr {$minutes}min";
            // $formattedTimeDifference = "{$hours}hr {$minuteDifference}min";
        }
        $invoiceService->service_value = ceil($serviceAmount);
        return ceil($serviceAmount);
    }

}