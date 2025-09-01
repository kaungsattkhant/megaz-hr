<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\StaffResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TimeShiftResource;

class CheckInResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id ?? null,
            'staff_name' => $this->staff->name ?? null,
            'time_shift_id' => $this->time_shift_id ?? null,
            'check_in_date_time' => $this->check_in_date_time ?? null,
            'check_out_date_time' => $this->check_out_date_time ?? null,
            'check_in_photo_url' => $this->check_in_photo_url ?? null,
            'check_in_photo_path' => $this->check_in_photo_path ?? null,
            'check_out_photo_url' => $this->check_out_photo_url ?? null,
            'check_out_photo_path' => $this->check_out_photo_path ?? null,
            'is_current_checked_in' => $this->is_current_checked_in,
            'total' => $this->calculateTotalHours() ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'time_shift' => new TimeShiftResource($this->whenLoaded('timeShift')),
        ];
    }

    private function calculateTotalHours(): ?string
    {
        if ($this->check_out_date_time && $this->check_in_date_time) {
            $checkInTime = Carbon::parse($this->check_in_date_time);
            $checkOutTime = Carbon::parse($this->check_out_date_time);
            $totalMinutes = $checkOutTime->diffInMinutes($checkInTime);
            $hours = abs(intdiv($totalMinutes, 60));
            $minutes = abs($totalMinutes % 60);
            return sprintf('%d hours %d minutes', $hours, $minutes);
        }

        return null;
    }

    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        if ($resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $response = [
                'data' => $collection->collection,
                'links' => [
                    'first' => $resource->url(1),
                    'last' => $resource->url($resource->lastPage()),
                    'prev' => $resource->previousPageUrl(),
                    'next' => $resource->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $resource->currentPage(),
                    'from' => $resource->firstItem(),
                    'last_page' => $resource->lastPage(),
                    'links' => $resource->linkCollection()->toArray(),
                    'path' => $resource->path(),
                    'per_page' => $resource->perPage(),
                    'to' => $resource->lastItem(),
                    'total' => $resource->total(),
                ],
                'success' => true
            ];
            $collection->withResponse(request(), response()->json($response));
        }
        
        return $collection;
    }
}
