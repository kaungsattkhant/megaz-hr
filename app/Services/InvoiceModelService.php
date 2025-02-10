<?php

namespace App\Services;

use App\Models\Entity;
use App\Models\Account;
use App\Models\RoomSession;
use App\Models\EntitySession;
use GuzzleHttp\Psr7\Response;
use App\Models\InvoiceSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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

    public function storeInvoiceSession($invoiceId, $entityId, $sessionDuration, $sessionPerPrice, $discountId)
    {
        // $startTime="22:10";
        // $endTime="00:10";

        $now = now();
        $startTime = now()->format('H:i');
        $endTime = $now->copy()->addHours((int) $sessionDuration)->format('H:i');
        // Check if endTime goes past midnight
        if ($startTime > $endTime) {
            $endDate = $now->copy()->addDay(); // Move to tomorrow
        } else {
            $endDate = $now->copy(); // Stay on the same day
        }
        $startDateTime = Carbon::parse($now->toDateString() . ' ' . $startTime);
        $endDateTime = Carbon::parse($endDate->toDateString() . ' ' . $endTime);
        $invoiceSession = InvoiceSession::create([
            'start_date_time' => $startDateTime,
            'end_date_time' => $endDateTime,
            'total_session_duration' => $sessionDuration,
            'total_session_price' => $sessionDuration * $sessionPerPrice,
            'session_unit_price' => $sessionPerPrice,
            'invoice_id' => $invoiceId,
            'entity_id' => $entityId,
        ]);
        $entitySesions = $this->getEntitySessionBySessionDuration($entityId, $startTime, $endTime);
        $entitySesionIds=$entitySesions->pluck('id');
        foreach($entitySesions as $entitySession){
            $createRoomSession=$invoiceSession->roomSessions()->create([
                'entity_session_id'=>$entitySession->id,
            ]);
            $entitySession->is_active=1;
            $entitySession->save();
        }
    }

    public function getEntitySessionBySessionDuration($entityId, $startTime, $endTime)
    {
        $takeSessions = EntitySession::select('id', 'start_time', 'end_time', 'is_active', 'spans_midnight', 'entity_id')
            ->where('entity_id', $entityId)
            ->where(function ($query) use ($startTime, $endTime) {
                if ($startTime < $endTime) {
                    // Normal case: within the same day (e.g., 15:00 - 16:00)
                    $query->whereTime('start_time', '<', $endTime)
                        ->whereTime('end_time', '>', $startTime);
                } else {
                    // Special case: across midnight (e.g., 23:06 - 01:06)
                    $query
                        ->where(function ($q) use ($startTime, $endTime) {
                        $q->whereTime('start_time', '<', $endTime)
                            ->orWhereTime('end_time', '>', $startTime);
                    })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                        // Case 1: Sessions starting before midnight and ending after
                        $q->whereTime('start_time', '>=', $startTime)
                            ->orWhereTime('end_time', '<=', $endTime);
                    })
                        ->orWhere(function ($q) {
                        // Case 2: Sessions that completely span over midnight (e.g., 22:00 - 02:00)
                        $q->whereTime('start_time', '>', '23:59')
                            ->orWhereTime('end_time', '<', '00:00');
                    });
                }

            })

            ->orderByRaw("
        CASE 
            WHEN start_time >= '00:00:00' AND start_time < '12:00:00' THEN 2 -- Sessions after midnight (00:xx)
            WHEN spans_midnight = 1 THEN 1 -- Sessions spanning midnight (23:xx - 00:xx)
            ELSE 0 -- Sessions before midnight (22:xx)
        END, start_time
    ")
            ->get();
        if ($takeSessions->isEmpty()) {
            ResponseMessage('Entity Session is invalid', 200);
        }
        return $takeSessions;
    }

    public function getTotalInvoiceSession($invoiceId){
        $invoiceSession=InvoiceSession::where('invoice_id',$invoiceId)
        ->select(
            'invoice_sessions.invoice_id',
            DB::raw('SUM(invoice_sessions.total_session_duration) as total_duration'),
            DB::raw('SUM(invoice_sessions.total_session_price) as total_session_value'),
            // DB::raw('COALESCE(SUM(invoice_sessions.total_session_price), 0) as total_session_value')

        )
        ->groupBy('invoice_sessions.invoice_id')
        ->first();
        return $invoiceSession;
    }

    public function checkIsActiveChangeRoom($entityId)
    {

        $isEntity=Entity::where('is_active',1)
        ->where('id',$entityId)
        ->first();
        if($isEntity){
            ResponseMessage('Entity is not available now',419);
        }
    }

}