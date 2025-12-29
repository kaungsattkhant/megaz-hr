<?php

namespace App\Http\Controllers\Staff;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $staffId = \UserData()->id;
        $staff = Staff::with('department', 'roles')->find($staffId);
        if (!$staff) {
            return ResponseData(null, 400, false, 'Staff not found.');
        }
        $departmentId = $staff->department_id;
        $roleIds = $staff->roles->pluck('id')->toArray();
        $search = $request->query('search');
        $currentDate = Carbon::now()->toDateTimeString();
        $result = Participant::selectRaw("
        COUNT(DISTINCT CASE 
            WHEN participantable_type = 'meeting'
            AND meetings.from_date >= ?
            THEN meetings.id 
        END) as meeting_count,

        COUNT(DISTINCT CASE 
            WHEN participantable_type = 'training'
            AND trainings.from_date >= ?
            THEN trainings.id 
        END) as training_count
    ", [$currentDate, $currentDate])
            ->leftJoin('meetings', function ($join) {
                $join->on('participants.participantable_id', '=', 'meetings.id')
                    ->where('participants.participantable_type', 'meeting');
            })
            ->leftJoin('trainings', function ($join) {
                $join->on('participants.participantable_id', '=', 'trainings.id')
                    ->where('participants.participantable_type', 'training');
            })
            ->where(function ($subQuery) use ($staffId, $departmentId, $roleIds) {
                $subQuery->where('staff_id', $staffId)
                    ->orWhere('department_id', $departmentId)
                    ->orWhereIn('role_id', $roleIds);
            })
            ->first();
        \ResponseData($result);
    }
}
