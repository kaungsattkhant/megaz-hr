<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ParticipantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        $notificationable = [
            // 'id' => $this->id ?? null,
            // 'title' => $this->title ?? null,
            // 'date_time' => $this->date_time ?? null,
            // 'from_date' => $this->from_date ?? null,
            // 'to_date' => $this->to_date ?? null,
            // 'place' => $this->place ?? null,
            // 'description' => $this->description ?? null,
            // 'participants' => $this->whenLoaded('participants', function() {
            //     $participantData = [];
            //     foreach ($this->participants as $participant) {
            //         $participantData[] = [
            //             'id' => $participant->id ?? null,
            //             'department_id' => $participant->department_id ?? null,
            //             'department_name' => $participant->department->name ?? null,
            //             'role_id' => $participant->role_id ?? null,
            //             'role_name' => $participant->role->name ?? null,
            //             'staff_id' => $participant->staff_id ?? null,
            //             'staff_name' => $participant->staff->name ?? null,
            //         ];
            //     }
            //     return $participantData;
            // }),
        ];
        if ($this->notification->notificationable_type === "meeting") {
            $notificationable['id'] = $this->id ?? null;
            $notificationable['title'] = $this->title ?? null;
            $notificationable['date_time'] = $this->date_time ?? null;
            $notificationable['from_date'] = $this->from_date ?? null;
            $notificationable['to_date'] = $this->to_date ?? null;
            $notificationable['place'] = $this->place ?? null;
            $notificationable['description'] = $this->description ?? null;
            $notificationable['participants'] = $this->whenLoaded('participants', function() {
                $participantData = [];
                foreach ($this->participants as $participant) {
                    $participantData[] = [
                        'id' => $participant->id ?? null,
                        'department_id' => $participant->department_id ?? null,
                        'department_name' => $participant->department->name ?? null,
                        'role_id' => $participant->role_id ?? null,
                        'role_name' => $participant->role->name ?? null,
                        'staff_id' => $participant->staff_id ?? null,
                        'staff_name' => $participant->staff->name ?? null,
                    ];
                }
                return $participantData;
            });
            $notificationable['type'] = $this->meeting_type ?? null;
            $notificationable['chaired_by_id'] = $this->chairedBy->id ?? null;
            $notificationable['name'] = $this->chairedBy->name ?? null;
        }
        if ($this->notification->notificationable_type === "training") {
            $notificationable['id'] = $this->id ?? null;
            $notificationable['title'] = $this->title ?? null;
            $notificationable['date_time'] = $this->date_time ?? null;
            $notificationable['from_date'] = $this->from_date ?? null;
            $notificationable['to_date'] = $this->to_date ?? null;
            $notificationable['place'] = $this->place ?? null;
            $notificationable['description'] = $this->description ?? null;
            $notificationable['participants'] = $this->whenLoaded('participants', function() {
                $participantData = [];
                foreach ($this->participants as $participant) {
                    $participantData[] = [
                        'id' => $participant->id ?? null,
                        'department_id' => $participant->department_id ?? null,
                        'department_name' => $participant->department->name ?? null,
                        'role_id' => $participant->role_id ?? null,
                        'role_name' => $participant->role->name ?? null,
                        'staff_id' => $participant->staff_id ?? null,
                        'staff_name' => $participant->staff->name ?? null,
                    ];
                }
                return $participantData;
            });
            $notificationable['type'] = $this->training_type ?? null;
            $notificationable['trained_by_id'] = $this->trainedBy->id ?? null;
            $notificationable['name'] = $this->trainedBy->name ?? null;
        }
        if ($this->notification->notificationable_type === "warning") {
            $notificationable['id'] = $this->id ?? null;
            $notificationable['title'] = $this->title ?? null;
            $notificationable['date_time'] = $this->date_time ?? null;
            $notificationable['from_date'] = $this->from_date ?? null;
            $notificationable['to_date'] = $this->to_date ?? null;
            $notificationable['place'] = $this->place ?? null;
            $notificationable['description'] = $this->description ?? null;
            $notificationable['participants'] = $this->whenLoaded('participants', function() {
                $participantData = [];
                foreach ($this->participants as $participant) {
                    $participantData[] = [
                        'id' => $participant->id ?? null,
                        'department_id' => $participant->department_id ?? null,
                        'department_name' => $participant->department->name ?? null,
                        'role_id' => $participant->role_id ?? null,
                        'role_name' => $participant->role->name ?? null,
                        'staff_id' => $participant->staff_id ?? null,
                        'staff_name' => $participant->staff->name ?? null,
                    ];
                }
                return $participantData;
            });
            $notificationable['type'] = $this->warning_type ?? null;
        }
        if ($this->notification->notificationable_type === "orgNew") {
            $notificationable['id'] = $this->id ?? null;
            $notificationable['title'] = $this->title ?? null;
            $notificationable['date_time'] = $this->date_time ?? null;
            $notificationable['from_date'] = $this->from_date ?? null;
            $notificationable['to_date'] = $this->to_date ?? null;
            $notificationable['place'] = $this->place ?? null;
            $notificationable['description'] = $this->description ?? null;
            $notificationable['participants'] = $this->whenLoaded('participants', function() {
                $participantData = [];
                foreach ($this->participants as $participant) {
                    $participantData[] = [
                        'id' => $participant->id ?? null,
                        'department_id' => $participant->department_id ?? null,
                        'department_name' => $participant->department->name ?? null,
                        'role_id' => $participant->role_id ?? null,
                        'role_name' => $participant->role->name ?? null,
                        'staff_id' => $participant->staff_id ?? null,
                        'staff_name' => $participant->staff->name ?? null,
                    ];
                }
                return $participantData;
            });
            $notificationable['type'] = $this->org_news_type ?? null;
        }
        if($this->notification->notificationable_type === "staff_timeshift"){
            $notificationable['staff_id'] = $this->staff_id ?? null;
            $notificationable['staff_name'] = $this->staff->name ?? null;
            $notificationable['status'] = $this->status ?? null;
            $notificationable['timeshift_id'] = $this->timeshift_id ?? null;
            $notificationable['timeshift'] = $this->whenLoaded('timeshift', function() {

            return [
                    'id' => $this->timeshift->id ?? null,
                    'shift_id' => $this->timeshift->shift_id ?? null,
                    'shift_name' => $this->timeshift->shift->name ?? null,
                    'from_time' => $this->timeshift->from_time ?? null,
                    'to_time' => $this->timeshift->to_time ?? null,
                ];
            }) ?? null;
            
            $notificationable['area'] = $this->whenLoaded('area', function() {
                return [
                    'id' => $this->area->id ?? null,
                    'name' => $this->area->name ?? null,
                ];
            }) ?? null;
        }
        if($this->notification->notificationable_type === "staff_equipment_handover"){
            $notificationable['id'] = $this->id ?? null;
            $notificationable['from_staff_id'] = $this->from_staff_id ?? null;
            $notificationable['from_staff_name'] = $this->fromStaff->name ?? null;
            $notificationable['to_staff_id'] = $this->to_staff_id ?? null;
            $notificationable['to_staff_name'] = $this->toStaff->name ?? null;
            $notificationable['staff_timeshift_id'] = $this->staff_timeshift_id ?? null;
            $notificationable['handover_date'] = $this->handover_date ?? null;
            $notificationable['notes'] = $this->notes ?? null;
            $notificationable['status'] = $this->status ?? null;
            // $notificationable['handover_items'] = $this->staffEquipmentHandoverItems ?? [];
        }
        if($this->notification->notificationable_type=== 'objective_staff'){
            $notificationable['id'] = $this->id;
            $notificationable['start_date'] = $this->start_date;
            $notificationable['end_date'] = $this->end_date;
            $notificationable['status'] = $this->status;
            $notificationable['okr_point'] = $this->okr_point;
        }

        return $notificationable;
    }
}
