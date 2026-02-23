<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MeetingMinuteStoreRequest;
use App\Http\Resources\MeetingMinuteListResource;
use App\Models\Meeting;
use App\Models\MeetingMinute;
use App\Repositories\MeetingMinute\MeetingMinuteRepositoryInterface;
use Illuminate\Http\Request;

class MeetingMinuteController extends Controller
{
    private MeetingMinuteRepositoryInterface $meetingMinuteRepo;

    public function __construct(MeetingMinuteRepositoryInterface $meetingMinuteRepo)
    {
        $this->meetingMinuteRepo = $meetingMinuteRepo;
    }

    public function index(Request $request)
    {
        $meetingMinutes = $this->meetingMinuteRepo->list($request);
        return MeetingMinuteListResource::collection($meetingMinutes);
    }

    public function store(MeetingMinuteStoreRequest $request)
    {
        $meetingMinute = $this->meetingMinuteRepo->updateOrCreate($request->all());
        return new MeetingMinuteListResource($meetingMinute);
    }

    public function show(MeetingMinute $meetingMinute)
    {
        $meetingMinute = $this->meetingMinuteRepo->detail($meetingMinute);
        return new MeetingMinuteListResource($meetingMinute);
    }

}
