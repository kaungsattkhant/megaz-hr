<?php

namespace App\Services;

use App\Models\Entity;
use App\Models\Account;
use App\Models\RoomSession;
use App\Models\EntitySession;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;



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
        if (!$invoiceService->is_active) {
            // return $invoiceService;
        }
        $startDateTime = Carbon::parse($invoiceService->start_date);
        $nowDateTime = Carbon::parse(now());
        $differenceBeforeStartDate = $startDateTime->diffInMinutes($nowDateTime);

        $currentDateTime = Carbon::parse($end_time);
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
        if ($differenceBeforeStartDate < 0) {
            $invoiceService->service_value = 0;
            $invoiceService->minutes = "0min";
        } else {
            $invoiceService->service_value = ceil($serviceAmount);
            $invoiceService->minutes = $formattedTimeDifference;
        }
        $invoiceService->price_per_hour = $pricePerHour;
        return $invoiceService;
        // }
    }

    public function getServiceValue($invoiceService, $end_time)
    {
        if (!$invoiceService->is_active) {
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

    public function changeRoomForEndlessTime($invoice, $newEntity)
    {
        $currentRoomSession = $invoice->currentSession;
        $roomSession = $invoice->roomSession;
        $latestRoomSession = $invoice->latestSession;
        $firstRoomSession = $roomSession->first();
        $current_time = now();
        $oldEntitySession = $this->getCurrentEntitySessionByEntity($latestRoomSession->entitySession->entity_id);
        $currentStartTime = Carbon::parse($oldEntitySession->start_time);
        $useCurrentSession = $currentStartTime->diffInHours($current_time);
        return $useCurrentSession;
    }

    public function getCurrentEntitySessionByEntity($entityId)
    {
        // dd($startTime);
        $startTime = "2024-11-13 12:24:42";
        $current_time = now();
        $entitySession = EntitySession::where('entity_id', $entityId)
            ->whereTime('start_time', '<=', $current_time)
            ->whereTime('end_time', '>=', $current_time)
            ->first();
        return $entitySession;
    }

    public function accountByCode($code)
    {
        $account = Account::getByAccountCode($code);
        if (!$account) {
            ResponseMessage('Account is required', 419);
        }
        return $account;
    }

    public function checkOrderStatus($orderItems)
    {
        $checkIsNotYeyOrder = $orderItems->whereIn('status', 'not yet')
            ->first();
        if ($checkIsNotYeyOrder) {
            ResponseMessage('Order Item need to confirm first', 419);
        }
        return true;
    }

    public function updateIsActive($invoiceId, $isActive)
    {
        $invoiceRoomSessionUpdated = RoomSession::where('invoice_id', $invoiceId)
            ->update(['is_active' => $isActive]);

        Log::info('Room Sesion Status update is succesfully');
        return $invoiceRoomSessionUpdated;

    }

}